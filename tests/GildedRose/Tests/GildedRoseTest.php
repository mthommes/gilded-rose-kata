<?php

namespace GildedRose\Tests;

use PHPUnit\Framework\TestCase;
use GildedRose\Item;
use GildedRose\Program;

function dbg($var, $continue = 0, $element = "pre") {
	echo "<" . $element . ">";
	echo "Vartype: " . gettype($var) . "\n";
	if ( is_array($var) ) echo "Elements: " . count($var) . "\n\n";
	elseif ( is_string($var) ) echo "Length: " . strlen($var) . "\n\n";
	print_r($var);
	echo "</" . $element . ">";
	if (!$continue) exit();
}

class GildedRoseTest extends TestCase
{
	private $items = array();

	public function __construct()
	{
		$this->items["+5 Dexterity Vest"] = array(
			"name" => "+5 Dexterity Vest",
			"sellIn" => 10,
			"quality" => 20
		);
		$this->items["Aged Brie"] = array(
			"name" => "Aged Brie",
			"sellIn" => 2,
			"quality" => 0
		);
		$this->items["Elixir of the Mongoose"] = array(
			"name" => "Elixir of the Mongoose",
			"sellIn" => 5,
			"quality" => 7
		);
		$this->items["Sulfuras, Hand of Ragnaros"] = array(
			"name" => "Sulfuras, Hand of Ragnaros",
			"sellIn" => 0,
			"quality" => 80
		);
		$this->items["Backstage passes to a TAFKAL80ETC concert"] = array(
			"name" => "Backstage passes to a TAFKAL80ETC concert",
			"sellIn" => 15,
			"quality" => 20
		);
		$this->items["Conjured Mana Cake"] = array(
			"name" => "Conjured Mana Cake",
			"sellIn" => 3,
			"quality" => 6
		);
	}

	public function testItPrintsAllItemsFromMain()
	{
		$output = file_get_contents(__DIR__ . '/output_fixture.txt');
		$this->expectOutputString($output);
		$program = new Program($this->items, 100);
	}

	/**
	 * "Once the sell by date has passed, Quality degrades twice as fast"
	 */
	public function testQualityRapidDegradation()
	{
		// Given a new item to test
		$this->items["Test 1"] = array(
			"name" => "Test 1",
			"sellIn" => 1,
			"quality" => 15
		);
		// When I run the program for 3 days
		$program = new Program($this->items, 3);
		// And I fetch the updated items
		$items = $program->getItems();
		// And I grab the test item
		$item = $items["Test 1"];
		// Then the test item should have the correct sellIn and quality
		assert($item->sellIn == -2, "sellIn check");
		assert($item->quality == 10, "quality check");
	}

	/**
	 * "The Quality of an item is never negative"
	 */
	public function testQualityNeverNegative()
	{
		// Given a new item to test
		$this->items["Test 1"] = array(
			"name" => "Test 1",
			"sellIn" => 10,
			"quality" => 2
		);
		// When I run the program for 4 days
		$program = new Program($this->items, 4);
		// And I fetch the updated items
		$items = $program->getItems();
		// And I grab the test item
		$item = $items["Test 1"];
		// Then the test item should have the correct sellIn and quality
		assert($item->sellIn == 6, "sellIn check");
		assert($item->quality == 0, "quality check");
	}

	/*public function testX()
	{
		// Given 5 days have passed.
		$program = new Program($this->items, 5);
print_r($program->getItems());exit;
		//$this->assertEquals(1, 0);
	}*/
}
