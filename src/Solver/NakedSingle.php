<?php

namespace Sudoku\Solver;

use Sudoku\Box\Box;
use Sudoku\Box\Number;

class NakedSingle {
    public static function solve(Box $box):Box {
        $unsolves = [];
        foreach($box->getUnsolves() as $number) {
            [$r, $c, $b] = $number->getRCB();
            $mask = $box->getMask($r, $c, $b);
            $number->eliminate($mask);
            if($number->getDigit() > 0) {
                $box->placeNumber($number);
            }else{
                $unsolves[] = $number;
            }
        }
        $box->setUnsolves($unsolves);
        return $box;
    }
}
