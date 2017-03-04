<?php

namespace GildedRose;

class ConjuredUpdater extends ItemUpdater
{
    public function updateQuality()
    {
        if ($this->isAfterSellDate()) {
            return $this->decreaseQuality(4);
        }

        $this->decreaseQuality(2);
    }
}
