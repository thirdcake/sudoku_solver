<?php

namespace Sudoku\Solver;

use Sudoku\Box\Box;
use Sudoku\Box\Helper;

class NakedSingle {
    public static function solve(Box $box):Box {
        $num = -1;
        $idx = -1;
        foreach($box->getUnsolves() as $index => $_bool) {
            if(Helper::popcount($box->getCandidates($index))===1) {
                $candidates = $box->getCandidates($index);
                $num = Helper::getNumber($candidates);
                $idx = $index;
                break;
            }
        }
        if(0 < $num && 0 <= $idx) {
            $box->append($num, $idx);
            if($box->valid()) {
                $box = self::solve($box);
            }
        }
        return $box;
    }
}
