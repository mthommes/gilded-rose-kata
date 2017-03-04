<?php

namespace GildedRose;

class NullUpdater extends ItemUpdater
{
    public function updateSellIn()
    {
        // never has to be sold
    }

    public function updateQuality()
    {
        // never changes quality
    }
}
