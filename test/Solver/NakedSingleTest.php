<?php

use Sudoku\Box\Box;
use Sudoku\Box\Number;
use Sudoku\Solver\Solver;
use Sudoku\Solver\NakedSingle;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class NakedSingleTest extends TestCase {

    #[Test]
    public function can_insert_one():void {
        define('POPCOUNT', include dirname(__DIR__,2)."/src/Box/popcount.php");
        $problem = <<<EOL
        023456789
        000000000
        000000000
        000000000
        000000000
        000000000
        000000000
        000000000
        000000000
        EOL;
        $box = new Box($problem);
        $box = NakedSingle::solve($box);
        $this->assertSame(2, $box->dumpNumber(0,1));
        //$this->assertSame(1, $box->dumpNumber(0,0));
    }
}

