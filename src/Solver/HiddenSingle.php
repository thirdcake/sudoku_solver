<?php

namespace Sudoku\Solver;

use Sudoku\Box\Box;
use Sudoku\Box\Helper;

class HiddenSingle {
    public static function solve(Box $box):Box {
        $num = -1;
        $row = -1;
        $col = -1;
        $unsolves = $box->getUnsolves();
        foreach($unsolves as $index => $_bool) {
            $candidates = $box->getCandidates($index);
            $row = intdiv($index, 9);
            $col = $index % 9;

            // 行を探索
            $mask = 0;
            for($c=0; $c<9; $c++) {
                if($c===$col) continue;
                $mask |= $box->getCandidates($row * 9 + $c);
            }
            $bit = $candidates & (~$mask);
            if(Helper::popcount($bit)===1) {
                $num = Helper::getNumber($bit);
                break;
            }

            // 列を探索
            $mask = 0;
            for($r=0; $r<9; $r++) {
                if($r===$row) continue;
                $mask |= $box->getCandidates($r * 9 + $col);
            }
            $bit = $candidates & (~$mask);
            if(Helper::popcount($bit)===1) {
                $num = Helper::getNumber($bit);
                break;
            }

            // blockを探索
            $mask = 0;
            $or = intdiv($row, 3) * 3;  // 原点のr
            $oc = intdiv($col, 3) * 3;  // 原点のc
            $diff = ($row - $or) * 3 + ($col - $oc);  // 原点からの差
            for($b=0; $b<9; $b++) {
                if($b===$diff) continue;
                $nr = $or + intdiv($b, 3);
                $nc = $oc + $b % 3;
                $mask |= $box->getCandidates($nr * 9 + $nc);
            }
            $bit = $candidates & (~$mask);
            if(Helper::popcount($bit)===1) {
                $num = Helper::getNumber($bit);
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
