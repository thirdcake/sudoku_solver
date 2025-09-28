<?php

use Sudoku\Box\Box;
use Sudoku\Box\Helper;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class HelperTest extends TestCase {

    #[Test]
    public function popcount():void {
        $this->assertSame(9, Helper::popcount(511));
    }

}
