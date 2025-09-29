<?php

namespace Sudoku\Solver;

use Sudoku\Box\Box;
use Sudoku\Box\Helper;

class NakedPair {
    public static function solve(Box $box):Box {
        $num = -1;
        $row = -1;
        $col = -1;
        $unsolves = $box->getUnsolves();
        $set = [];
        foreach($unsolves as $index => $_bool) {
            $r = intdiv($index, 9);
            $c = $index % 9;
            $b = intdiv($r, 3) * 3 + intdiv($c, 3);
            $candidates = $box->getCandidates($index);
            $set[$candidates]['row'][$r][] = $index;
            $set[$candidates]['col'][$c][] = $index;
            $set[$candidates]['block'][$b][] = $index;
        }
        foreach($set as $candidates=>$arr) {
            $count = Helper::popcount($candidates);
            foreach($arr['row'] as $r => $idxs) {
                $cnt = count($idxs);
                if($cnt < 2 || 4 < $cnt) continue;
                if($cnt === $count) {
                    for($c=0; $c<9; $c++) {
                        $idx = $r * 9 + $c;
                        if(in_array($idx, $idxs, true)) continue;
                        $box->removeCandidates($idx, $candidates);
                    }
                }
            }
            foreach($arr['col'] as $c => $idxs) {
                $cnt = count($idxs);
                if($cnt<2 || 4 < $cnt) continue;
                if($cnt === $count) {
                    for($r=0; $r<9; $r++) {
                        $idx = $r * 9 + $c;
                        if(in_array($idx, $idxs, true)) continue;
                        $box->removeCandidates($idx, $candidates);
                    }
                }
            }
            foreach($arr['block'] as $b => $idxs) {
                $cnt = count($idxs);
                if($cnt<2 || 4 < $cnt) continue;
                if($cnt === $count) {
                    for($p=0; $p<9; $p++) {
                        $idx = intdiv($b, 3) * 27 + ($b % 3) * 3 + intdiv($p, 3) * 9 + ($p % 3);
                        if(in_array($idx, $idxs, true)) continue;
                        $box->removeCandidates($idx, $candidates);
                    }
                }
            }
        }

        return $box;
    }
}

