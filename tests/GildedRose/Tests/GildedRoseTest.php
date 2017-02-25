<?php

namespace GildedRose\Tests;

use GildedRose\Item;
use GildedRose\Program;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testUpdateQualityOfNormalItemBeforeSellDate()
    {
        $item = new Item(['name' => 'Milk', 'sellIn' => 5, 'quality' => 10]);
        $this->runProgramWith($item);
        $this->assertEquals(4, $item->sellIn);
        $this->assertEquals(9, $item->quality);
    }

    public function testUpdateQualityOfNormalItemOnSellDate()
    {
        $item = new Item(['name' => 'Milk', 'sellIn' => 0, 'quality' => 10]);
        $this->runProgramWith($item);
        $this->assertEquals(-1, $item->sellIn);
        $this->assertEquals(8, $item->quality);
    }

    public function testUpdateQualityOfNormalItemAfterSellDate()
    {
        $item = new Item(['name' => 'Milk', 'sellIn' => -5, 'quality' => 10]);
        $this->runProgramWith($item);
        $this->assertEquals(-6, $item->sellIn);
        $this->assertEquals(8, $item->quality);
    }

    public function testUpdateQualityOfNormalItemOfZeroQuality()
    {
        $item = new Item(['name' => 'Milk', 'sellIn' => 5, 'quality' => 0]);
        $this->runProgramWith($item);
        $this->assertEquals(4, $item->sellIn);
        $this->assertEquals(0, $item->quality);
    }

    public function testUpdateQualityOfAgedBrieBeforeSellDate()
    {
        $item = new Item(['name' => 'Aged Brie', 'sellIn' => 5, 'quality' => 10]);
        $this->runProgramWith($item);
        $this->assertEquals(4, $item->sellIn);
        $this->assertEquals(11, $item->quality);
    }

    public function testUpdateQualityOfAgedBrieBeforeSellDateWithMaxQuality()
    {
        $item = new Item(['name' => 'Aged Brie', 'sellIn' => 5, 'quality' => 50]);
        $this->runProgramWith($item);
        $this->assertEquals(4, $item->sellIn);
        $this->assertEquals(50, $item->quality);
    }

    public function testUpdateQualityOfAgedBrieOnSellDate()
    {
        $item = new Item(['name' => 'Aged Brie', 'sellIn' => 0, 'quality' => 10]);
        $this->runProgramWith($item);
        $this->assertEquals(-1, $item->sellIn);
        $this->assertEquals(12, $item->quality);
    }

    public function testUpdateQualityOfAgedBrieOnSellDateNearMaxQuality()
    {
        $item = new Item(['name' => 'Aged Brie', 'sellIn' => 0, 'quality' => 49]);
        $this->runProgramWith($item);
        $this->assertEquals(-1, $item->sellIn);
        $this->assertEquals(50, $item->quality);
    }

    public function testUpdateQualityOfAgedBrieOnSellDateWithMaxQuality()
    {
        $item = new Item(['name' => 'Aged Brie', 'sellIn' => 0, 'quality' => 50]);
        $this->runProgramWith($item);
        $this->assertEquals(-1, $item->sellIn);
        $this->assertEquals(50, $item->quality);
    }

    public function testUpdateQualityOfAgedBrieAfterSellDate()
    {
        $item = new Item(['name' => 'Aged Brie', 'sellIn' => -5, 'quality' => 8]);
        $this->runProgramWith($item);
        $this->assertEquals(-6, $item->sellIn);
        $this->assertEquals(10, $item->quality);
    }

    public function testUpdateQualityOfAgedBrieAfterSellDateWithMaxQuality()
    {
        $item = new Item(['name' => 'Aged Brie', 'sellIn' => -5, 'quality' => 50]);
        $this->runProgramWith($item);
        $this->assertEquals(-6, $item->sellIn);
        $this->assertEquals(50, $item->quality);
    }

    private function runProgramWith(Item $item)
    {
        $sut = new Program([$item]);
        $sut->UpdateQuality();
    }
}
