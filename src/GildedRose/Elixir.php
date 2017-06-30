<?php

namespace GildedRose;

class Elixir implements ItemInterface {
    public $item;
    public function __construct(Item $item) {
        $this->item = $item;
    }
    public function UpdateQuality() {
        if ($this->item->quality > 0) {
            $this->item->quality -= 1;
        }
        $this->item->sellIn -= 1;
        if ($this->item->sellIn < 0) {
            if ($this->item->quality > 0) {
                $this->item->quality -= 1;
            }
        }
        return $this->item;
    }
}
