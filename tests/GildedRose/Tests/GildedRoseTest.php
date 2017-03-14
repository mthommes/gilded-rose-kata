<?php

namespace GildedRose\Tests;

use PHPUnit\Framework\TestCase;
use GildedRose\Item;
use GildedRose\Program;

class GildedRoseTest extends TestCase
{
	private $items = array();

	public function __construct()
	{
		$this->items[] = new Item(array('name' => "+5 Dexterity Vest",'sellIn' => 10,'quality' => 20));
		$this->items[] = new Item(array('name' => "Aged Brie",'sellIn' => 2,'quality' => 0));
		$this->items[] = new Item(array('name' => "Elixir of the Mongoose",'sellIn' => 5,'quality' => 7));
		$this->items[] = new Item(array('name' => "Sulfuras, Hand of Ragnaros",'sellIn' => 0,'quality' => 80));
		$this->items[] = new Item(array('name' => "Backstage passes to a TAFKAL80ETC concert", 'sellIn' => 15, 'quality' => 20));
		$this->items[] = new Item(array('name' => "Conjured Mana Cake",'sellIn' => 3,'quality' => 6));
	}

	public function testItPrintsAllItemsFromMain()
	{
		$output = file_get_contents(__DIR__ . '/output_fixture.txt');
		$this->expectOutputString($output);
		$program = new Program($this->items, 100);
	}

	public function testQualityDegradation()
	{
		//$program = Program::main(2);
		//$this->assertEquals(1, 0);
	}	
}
