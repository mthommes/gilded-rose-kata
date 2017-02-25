<?php

namespace GildedRose\Tests;

use PHPUnit\Framework\TestCase;
use GildedRose\Program;

class GildedRoseTest extends TestCase
{
    public function testItPrintsAllItemsFromMain()
     {
         $output = file_get_contents(__DIR__ . '/output_fixture.txt');
         $this->expectOutputString($output);
         $program = Program::main(100);
     }
}
