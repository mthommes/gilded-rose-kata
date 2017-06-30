<?php

namespace GildedRose;

class Legendary implements ItemInterface {
    public $item;
    public function __construct(Item $item) {
        $this->item = $item;
    }
    public function UpdateQuality() {
        return $this->item;
    }
}
