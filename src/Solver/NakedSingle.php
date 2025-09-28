<?php

namespace Sudoku\Solver;

use Sudoku\Box\Box;
use Sudoku\Box\Helper;

class NakedSingle {
    public static function solve(Box $box):Box {
        $num = -1;
        $row = -1;
        $col = -1;
        foreach($box->getUnsolves() as $index => $_bool) {
            if(Helper::popcount($box->getCandidates($index))===1) {
                $candidates = $box->getCandidates($index);
                $num = Helper::getNumber($candidates);
                $row = intdiv($index, 9);
                $col = $index % 9;
                break;
            }
        }
        if(0 < $num && 0 <= $row && 0 <= $col) {
            $box->append($num, $row, $col);
            $box = self::solve($box);
        }
        return $box;
    }
}
