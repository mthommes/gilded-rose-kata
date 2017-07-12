<?php

namespace GildedRose\Tests;

use PHPUnit\Framework\TestCase;
use GildedRose\Item;
use GildedRose\Program;

class GildedRoseTest extends TestCase {

	private $items;

	public function setUp() {
		$this->items = array(
			new Item(array( 'name' => "+5 Dexterity Vest",'sellIn' => 10,'quality' => 20)),
			new Item(array( 'name' => "Aged Brie",'sellIn' => 2,'quality' => 0)),
			new Item(array( 'name' => "Elixir of the Mongoose",'sellIn' => 5,'quality' => 7)),
			new Item(array( 'name' => "Sulfuras, Hand of Ragnaros",'sellIn' => 0,'quality' => 80)),
			new Item(array(
			 'name' => "Backstage passes to a TAFKAL80ETC concert",
			 'sellIn' => 15,
			 'quality' => 20
			)),
			new Item(array('name' => "Conjured Mana Cake",'sellIn' => 3,'quality' => 6)),
		);
	}

	public function testItPrintsAllItemsFromMain() {
		$output = file_get_contents(__DIR__ . '/output_fixture.txt');
		$this->expectOutputString($output);
		$program = Program::main($this->items, 100);
	}

	/**
	 * Once the sell by date has passed, Quality degrades twice as fast
	 */
	public function XtestQualityRapidDegradation() {
		// Given a new item to test
		$this->items["Test 1"] = array(
			"name" => "Test 1",
			"sellIn" => 1,
			"quality" => 15
		);
		// When I run the program for 3 days
		$program = Program::main(3);
		// And I fetch the updated items
		$items = $program->getItems();
		// And I grab the test item
		$item = $items["Test 1"];
		// Then the test item should have the correct sellIn and quality
		assert($item->sellIn == -2, "sellIn check");
		assert($item->quality == 10, "quality check");
	}

	/**
	 * The Quality of an item is never negative
	 */
	public function XtestQualityNeverNegative() {
		// Given a new item to test
		$this->items["Test 1"] = array(
			"name" => "Test 1",
			"sellIn" => 10,
			"quality" => 2
		);
		// When I run the program for 4 days
		$program = Program::main(4);
		// And I fetch the updated items
		$items = $program->getItems();
		// And I grab the test item
		$item = $items["Test 1"];
		// Then the test item should have the correct sellIn and quality
		assert($item->sellIn == 6, "sellIn check");
		assert($item->quality == 0, "quality check");
	}

	/**
	 * The Quality of an item is never more than 50
	 */
	public function XtestQualityCeiling() {
		// When I run the program for 30 days (enough to pass 50 quality for "Aged Brie")
		$program = Program::main(30);
		// And I fetch the updated items
		$items = $program->getItems();
		// And I grab the test item
		$item = $items["Aged Brie"];
		// Then the test item should have the correct sellIn and quality
		assert($item->sellIn == -28, "sellIn check");
		// Should not have gone beyond 50
		assert($item->quality == 50, "quality check");
	}

	/**
	 * "Aged Brie" actually increases in Quality the older it gets
	 */
	public function XtestAgedBrie() {
		// Given we are testing the "Aged Brie" item
		$initialQuality = $this->items["Aged Brie"]["quality"];
		// When I run the program for 1 day
		$program = Program::main(1);
		// And I fetch the updated items
		$items = $program->getItems();
		// And I grab the test item
		$item = $items["Aged Brie"];
		assert($item->quality > $initialQuality, "Aged Brie increase check");
	}

	/**
	 * "Sulfuras", being a legendary item, never has to be sold or decreases in Quality
	 */
	public function XtestSulfuras() {
		// Given we are testing the "Sulfuras, Hand of Ragnaros" item
		$initialState = $this->items["Sulfuras, Hand of Ragnaros"];
		// When I run the program for 5 days
		$program = Program::main(5);
		// And I fetch the updated items
		$items = $program->getItems();
		// And I grab the test item
		$item = $items["Sulfuras, Hand of Ragnaros"];
		// Then the sell by date and quality should not have changed
		assert($initialState["sellIn"] == $item->sellIn, "Sulfuras sellIn check");
		assert($initialState["quality"] == $item->quality, "Sulfuras quality check");
	}
}
