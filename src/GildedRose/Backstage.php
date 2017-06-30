<?php

namespace GildedRose;

class Backstage implements ItemInterface {
    public $item;
    public function __construct(Item $item) {
        $this->item = $item;
    }
    public function UpdateQuality() {
        if ($this->item->quality < 50) {
            $this->item->quality += 1;
            if ($this->item->sellIn < 11) {
                if ($this->item->quality < 50) {
                    $this->item->quality += 1;
                }
            }
            if ($this->item->sellIn < 6) {
                if ($this->item->quality < 50) {
                    $this->item->quality += 1;
                }
            }
        }
        $this->item->sellIn -= 1;
        if ($this->item->sellIn < 0) {
            $this->item->quality = 0;
        }
        return $this->item;
    }
}
