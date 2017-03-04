<?php

namespace GildedRose;

class BackstagePassUpdater extends ItemUpdater
{
    public function updateQuality()
    {
        if ($this->isAfterSellDate()) {
            return $this->decreaseQualityToZero();
        }

        if ($this->thereAreFiveDaysOrLess()) {
            return $this->increaseQuality(3);
        }

        if ($this->thereAreTenDaysOrLess()) {
            return $this->increaseQuality(2);
        }

        $this->increaseQuality(1);
    }

    private function thereAreFiveDaysOrLess()
    {
        return $this->item->sellIn < 5;
    }

    private function thereAreTenDaysOrLess()
    {
        return $this->item->sellIn < 10;
    }
}
