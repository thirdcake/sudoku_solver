<?php

use Sudoku\Box\Box;
use Sudoku\Box\Helper;
use Sudoku\Solver\Solver;
use Sudoku\Solver\NakedSingle;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class NakedSingleTest extends TestCase {

    #[Test]
    public function can_insert_one():void {
        $problem = <<<EOL
        000230000
        045000000
        060000000
        700000000
        800000000
        900000000
        000000000
        000000000
        000000000
        EOL;
        $box = Helper::problemParse($problem);
        $box = NakedSingle::solve($box);
        $this->assertSame(1, $box->getCandidates(0));
    }
}

