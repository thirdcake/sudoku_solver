<?php

use Sudoku\Box\Number;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class NumberTest extends TestCase {

    #[Test]
    public function isOriginal_true():void {
        $number = new Number(1, false, true, 0, 0);
        $this->assertTrue($number->isOriginal());
    }
}

