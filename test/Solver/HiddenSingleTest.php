<?php

use Sudoku\Box\Box;
use Sudoku\Box\Helper;
use Sudoku\Solver\Solver;
use Sudoku\Solver\HiddenSingle;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class HiddenSingleTest extends TestCase {

    #[Test]
    public function can_row_single():void {
        $problem = <<<EOL
        000000000
        000000001
        000000010
        000000100
        000001000
        000010000
        000100000
        001000000
        010000000
        EOL;
        $box = Helper::problemParse($problem);
        $box = HiddenSingle::solve($box);
        $this->assertSame(1, $box->digit(0));
    }

    #[Test]
    public function can_col_single():void {
        $problem = <<<EOL
        000000002
        000000000
        000000010
        000000100
        000001000
        000010000
        000100000
        001000000
        010000000
        EOL;
        $box = Helper::problemParse($problem);
        $box = HiddenSingle::solve($box);
        $this->assertSame(1, $box->digit(0));
    }

    #[Test]
    public function can_block_single():void {
        $problem = <<<EOL
        023000000
        456000000
        789000000
        000000000
        000000000
        000000000
        000000000
        000000000
        000000000
        EOL;
        $box = Helper::problemParse($problem);
        $box = HiddenSingle::solve($box);
        $this->assertSame(1, $box->digit(0));
    }
}

