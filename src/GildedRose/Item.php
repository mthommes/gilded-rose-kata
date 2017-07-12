<?php

namespace GildedRose;

class Item
{
    public $name;
    public $sellIn;
    public $quality;

    public function __construct(array $parts)
    {
        foreach ($parts as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }
}

interface ItemInterface {
    // Require each implementation to define this method
    public function UpdateQuality();
}

class ItemClient {
    public $itemType;

    public function __construct(Item $item) {
        // Determine the type of each item based on it's name
        // Then load the specific class that implements ItemInterface and determine quality
        switch ($item->name) {
            case "+5 Dexterity Vest":
                $this->itemType = new Dexterity($item);
            break;
            case "Elixir of the Mongoose":
                $this->itemType = new Elixir($item);
            break;
            case "Backstage passes to a TAFKAL80ETC concert":
                $this->itemType = new Backstage($item);
            break;
            case "Aged Brie":
                $this->itemType = new Aged($item);
            break;
            case "Sulfuras, Hand of Ragnaros":
                $this->itemType = new Legendary($item);
            break;
            case "Conjured Mana Cake":
                $this->itemType = new Conjured($item);
            break;
        }
    }

    public function UpdateQuality() {
        // Call the ItemInterface UpdateQuality method (determined by the item type)
        $newItem = $this->itemType->UpdateQuality();
        return $newItem;
    }
}
