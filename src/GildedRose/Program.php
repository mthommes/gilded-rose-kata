<?php

namespace GildedRose;

/**
 * Hi and welcome to team Gilded Rose.
 *
 * As you know, we are a small inn with a prime location in a prominent city
 * ran by a friendly innkeeper named Allison. We also buy and sell only the
 * finest goods. Unfortunately, our goods are constantly degrading in quality
 * as they approach their sell by date. We have a system in place that updates
 * our inventory for us. It was developed by a no-nonsense type named Leeroy,
 * who has moved on to new adventures. Your task is to add the new feature to
 * our system so that we can begin selling a new category of items. First an
 * introduction to our system:
 *
 * - All items have a SellIn value which denotes the number of days we have to sell the item
 * - All items have a Quality value which denotes how valuable the item is
 * - At the end of each day our system lowers both values for every item
 *
 * Pretty simple, right? Well this is where it gets interesting:
 *
 * - Once the sell by date has passed, Quality degrades twice as fast
 * - The Quality of an item is never negative
 * - "Aged Brie" actually increases in Quality the older it gets
 * - The Quality of an item is never more than 50
 * - "Sulfuras", being a legendary item, never has to be sold or decreases in Quality
 * - "Backstage passes", like aged brie, increases in Quality as it's SellIn
 *   value approaches; Quality increases by 2 when there are 10 days or less and
 *   by 3 when there are 5 days or less but Quality drops to 0 after the concert
 *
 * We have recently signed a supplier of conjured items. This requires an
 * update to our system:
 *
 * - "Conjured" items degrade in Quality twice as fast as normal items
 *
 * Feel free to make any changes to the UpdateQuality method and add any new
 * code as long as everything still works correctly. However, do not alter the
 * Item class or Items property as those belong to the goblin in the corner who
 * will insta-rage and one-shot you as he doesn't believe in shared code
 * ownership (you can make the UpdateQuality method and Items property static
 * if you like, we'll cover for you).
 *
 * Just for clarification, an item can never have its Quality increase above
 * 50, however "Sulfuras" is a legendary item and as such its Quality is 80 and
 * it never alters.
 */

function dbg($var, $continue = 0, $element = "pre") {
	echo "<" . $element . ">";
	echo "Vartype: " . gettype($var) . "\n";
	if ( is_array($var) ) echo "Elements: " . count($var) . "\n\n";
	elseif ( is_string($var) ) echo "Length: " . strlen($var) . "\n\n";
	print_r($var);
	echo "</" . $element . ">";
	if (!$continue) exit();
}

class Program extends Item
{
    private $items = array();

    public function __construct(array $items, $days = 1) {
			foreach ($items as $item) {
				$this->items[$item["name"]] = new Item($item);
			}
			$this->generateDisplay($days);
    }

		public function getItems() {
			return $this->items;
		}

		public function updateQuality() {
			foreach ($this->items as $name => $info) {
				if ($name != "Aged Brie" && $name != "Backstage passes to a TAFKAL80ETC concert") {
					if ($info->quality > 0) {
						if ($name != "Sulfuras, Hand of Ragnaros") {
							$info->quality = $info->quality - 1;
						}
					}
				} else {
					if ($info->quality < 50) {
						$info->quality = $info->quality + 1;

						if ($name == "Backstage passes to a TAFKAL80ETC concert") {
							if ($info->sellIn < 11) {
								if ($info->quality < 50) {
									$info->quality = $info->quality + 1;
								}
							}

							if ($info->sellIn < 6) {
								if ($info->quality < 50) {
									$info->quality = $info->quality + 1;
								}
							}
						}
					}
				}

				if ($name != "Sulfuras, Hand of Ragnaros") {
					$info->sellIn = $info->sellIn - 1;
				}

				if ($info->sellIn < 0) {
					if ($name != "Aged Brie") {
						if ($name != "Backstage passes to a TAFKAL80ETC concert") {
							if ($info->quality > 0) {
								if ($name != "Sulfuras, Hand of Ragnaros") {
									$info->quality = $info->quality - 1;
								}
							}
						} else {
							$info->quality = $info->quality - $info->quality;
						}
					} else {
						if ($info->quality < 50) {
							$info->quality = $info->quality + 1;
						}
					}
				}
				// Set the modified item info on the global array.
				$this->items[$name] = $info;
			}
		}

		public function generateDisplay($days) {
			echo "OMGHAI!\n";
			// For each day, run the end-of-day routine.
			for ($day = 1; $day <= $days; $day++) {
				$this->updateQuality();
				echo "-------- day $day --------\n";
				echo sprintf("%50s - %7s - %7s\n", "Name", "SellIn", "Quality");
				foreach ($this->items as $item) {
					echo sprintf("%50s - %7d - %7d\n", $item->name, $item->sellIn, $item->quality);
				}
			}
		}
}
