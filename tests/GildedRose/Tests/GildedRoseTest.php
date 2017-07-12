<?php

namespace GildedRose\Tests;

use PHPUnit\Framework\TestCase;
use GildedRose\Item;
use GildedRose\ItemClient;
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
	public function testQualityRapidDegradation() {
		// Given an item to test (of type "Elixir")
		$item = new Item(array(
			"name" => "Elixir of the Mongoose",
			"sellIn" => 1,
			"quality" => 15
		));
		// When I run the program for 3 days
		for ($i = 1; $i <= 3; $i++) {
    	$itemClient = new ItemClient($item);
			$item = $itemClient->UpdateQuality();
		}
		// Then the test item should have the correct sellIn and quality
		assert($item->sellIn == -2, "sellIn check");
		assert($item->quality == 10, "quality check");
	}

	/**
	 * The Quality of an item is never negative
	 */
	public function testQualityNeverNegative() {
		// Given an item to test (of type "Elixir")
		$item = new Item(array(
			"name" => "Elixir of the Mongoose",
			"sellIn" => 10,
			"quality" => 2
		));
		// When I run the program for 4 days
		for ($i = 1; $i <= 4; $i++) {
    	$itemClient = new ItemClient($item);
			$item = $itemClient->UpdateQuality();
		}
		// Then the test item should have the correct sellIn and quality
		assert($item->sellIn == 6, "sellIn check");
		assert($item->quality == 0, "quality check");
	}

	/**
	 * The Quality of an item is never more than 50
	 */
	public function testQualityCeiling() {
		// Given an item to test (of type "Aged")
		$item = new Item(array(
			"name" => "Aged Brie",
			"sellIn" => 2,
			"quality" => 0
		));
		// When I run the program for 30 days (enough to pass 50 quality for "Aged Brie")
		for ($i = 1; $i <= 30; $i++) {
    	$itemClient = new ItemClient($item);
			$item = $itemClient->UpdateQuality();
		}
		// Then the test item should have the correct sellIn and quality
		assert($item->sellIn == -28, "sellIn check");
		// Should not have gone beyond 50
		assert($item->quality == 50, "quality check");
	}

	/**
	 * "Aged Brie" actually increases in Quality the older it gets
	 */
	public function testAgedBrie() {
		// Given an item to test (of type "Aged")
		$item = new Item(array(
			"name" => "Aged Brie",
			"sellIn" => 2,
			"quality" => 0
		));
		$initialQuality = 0;
		// When I run the program for 1 day
		for ($i = 1; $i <= 1; $i++) {
    	$itemClient = new ItemClient($item);
			$item = $itemClient->UpdateQuality();
		}
		assert($item->quality > $initialQuality, "Aged Brie increase check");
	}

	/**
	 * "Sulfuras", being a legendary item, never has to be sold or decreases in Quality
	 */
	public function testSulfuras() {
		// Given an item to test (of type "Sulfuras")
		$item = new Item(array(
			"name" => "Sulfuras, Hand of Ragnaros",
			"sellIn" => 0,
			"quality" => 80
		));
		$initialState = $item;
		// When I run the program for 5 days
		for ($i = 1; $i <= 5; $i++) {
    	$itemClient = new ItemClient($item);
			$item = $itemClient->UpdateQuality();
		}
		// Then the sell by date and quality should not have changed
		assert($initialState->sellIn == $item->sellIn, "Sulfuras sellIn check");
		assert($initialState->quality == $item->quality, "Sulfuras quality check");
	}
}
