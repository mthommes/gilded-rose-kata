<?php

namespace GildedRose;

abstract class ItemUpdater
{
    const QUALITY_MIN = 0;
    const QUALITY_MAX = 50;

    protected $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function update()
    {
        $this->updateSellIn();
        $this->updateQuality();
    }

    public function updateSellIn()
    {
        $this->item->sellIn -= 1;
    }

    abstract public function updateQuality();

    public function increaseQuality($amount)
    {
        if ($this->exceedsMaxQuality($amount)) {
            return $this->increaseQualityToMax();
        }

        $this->item->quality += $amount;
    }

    private function exceedsMaxQuality($amount)
    {
        return $this->item->quality + $amount > self::QUALITY_MAX;
    }

    private function increaseQualityToMax()
    {
        $this->item->quality = self::QUALITY_MAX;
    }

    public function decreaseQuality($amount)
    {
        if ($this->exceedsMinQuality($amount)) {
            return $this->decreaseQualityToZero();
        }

        $this->item->quality -= $amount;
    }

    public function exceedsMinQuality($amount)
    {
        return $this->item->quality - $amount < self::QUALITY_MIN;
    }

    public function decreaseQualityToZero()
    {
        $this->item->quality = 0;
    }

    public function isAfterSellDate()
    {
        return $this->item->sellIn < 0;
    }
}
