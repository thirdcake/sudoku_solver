<?php

namespace Sudoku\Solver;

use Sudoku\Box\Box;
use Sudoku\Box\Helper;

class HiddenPair {
    public static function solve(Box $box):Box {
        
        $unsolves = $box->getUnsolves();
        
        $group = [];
        
        foreach($unsolves as $index => $_bool) {
            $row = intdiv($index, 9);
            $col = $index % 9;
            $block = intdiv($row, 3) * 3 + intdiv($col, 3);

            $group['r'.$row][] = $index;
            $group['c'.$col][] = $index;
            $group['b'.$block][] = $index;
        }

        $box = self::bitSearch($group, $box);
        
        return $box;
    }

    private static function bitSearch(array $group, Box $box):Box {
        foreach($group as $idxs) {
            if(count($idxs) < 2) continue;

            for($j=1; $j<0x1FF; $j++) {  // bit 全探索
                $pc = Helper::popcount($j);
                if($pc < 2 || 4 < $pc) continue;

                $hasBit = [];
                foreach($idxs as $idx) {
                    if(($box->getCandidates($idx) | $j) === $j) {
                        $hasBit[] = $idx;
                    }
                }
                if(count($hasBit)===$pc) {
                    foreach($hasBit as $idx) {
                        $mask = ~$j & 0x1FF;
                        $box->removeCandidates($idx, $mask);
                    }
                }
            }
        }
        return $box;
    }
}
