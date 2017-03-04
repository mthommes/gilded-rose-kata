<?php

namespace GildedRose;

class NormalUpdater extends ItemUpdater
{
    public function updateQuality()
    {
        if ($this->isAfterSellDate()) {
            return $this->decreaseQuality(2);
        }

        $this->decreaseQuality(1);
    }
}
