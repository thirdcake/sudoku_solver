<?php

use Sudoku\Box\Box;
use Sudoku\Box\Number;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class BoxTest extends TestCase {

    #[Test]
    public function get_mask():void {
        $problem = <<<EOL
        023456000
        000000000
        000000000
        000000000
        000000000
        000000000
        700000000
        800000000
        900000000
        EOL;
        $box = new Box($problem);
        $mask = $box->getMask(0,0,0);
        $this->assertSame(510, $mask);
    }

    #[Test]
    public function get_unsolves():void {
        $problem = <<<EOL
        023456000
        000000000
        000000000
        000000000
        000000000
        000000000
        700000000
        800000000
        900000000
        EOL;
        $box = new Box($problem);
        $arr = $box->getUnsolves();
        $this->assertSame(81-8, count($arr));
    }

    #[Test]
    public function place_number():void {
        $problem = <<<EOL
        023456000
        000000000
        000000000
        000000000
        000000000
        000000000
        700000000
        800000000
        900000000
        EOL;
        $box = new Box($problem);
        $number = new Number(1,0,0);
        $box->placeNumber($number);
        $this->assertSame(1, $box->dumpNumber(0, 0));
    }
}
