<?php

namespace GildedRose;

class AgedUpdater extends ItemUpdater
{
    public function updateQuality()
    {
        if ($this->isAfterSellDate()) {
            return $this->increaseQuality(2);
        }

        $this->increaseQuality(1);
    }
}
