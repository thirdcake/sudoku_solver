<?php

namespace Sudoku\Solver;

use Sudoku\Box\Box;
use Sudoku\Box\Helper;

class Solver {
    private static array $solvers = [
        NakedSingle::class,
        HiddenSingle::class,
        NakedPair::class,
//        HiddenPair::class,   // 重い
    ];
    public static function solve(Box $box):Box {
        $solvers = self::$solvers;
        $prev_count = -1;
        $next_count = count($box->getUnsolves());
        while($prev_count !== $next_count) {
            foreach($solvers as $solver) {
                $box = $solver::solve($box);
            }
            $prev_count = $next_count;
            $next_count = count($box->getUnsolves());
        }
        
        $new_box = self::dfs($box);
        if($new_box->valid()) {
            return $new_box;
        }
        return $box;
    }

    /**
     * 基本的な考え方は、DFS
     * 可能性のあるものを試して、矛盾が出れば戻るを繰り返す
     * DFS の順番は、候補の少ないものから選んでいくと場合分けが減る
     * solvers が矛盾を見つけたら戻る
     */
    public static function dfs(Box $box):Box|false {

        if($box->solved()) return $box;

        $minIndex = -1;
        $minCount = 10;
        foreach($box->getUnsolves() as $index => $_bool) {
            $candidates = $box->getCandidates($index);
            $pc = Helper::popcount($candidates);
            if($pc <= 1) continue;
            if($pc < $minCount) {
                $minCount = $pc;
                $minIndex = $index;
            }
        }
        if($minIndex < 0) return $box;

        $candidates = $box->getCandidates($minIndex);

        // 候補のうち一つを入れてみて、矛盾が無ければ再帰する
        for($c=0; $c<9; $c++) {

            if(($candidates & (1 << $c)) === 0) continue;

            $nextBox = clone $box;
            $nextBox->append($c+1, $minIndex);
            if($nextBox->valid()===false) continue;
            
            // solver で変更が無くなるまで繰り返す
            $solvers = self::$solvers;
            $prev_count = -1;
            $next_count = count($nextBox->getUnsolves());
            while($prev_count !== $next_count) {
                foreach($solvers as $solver) {
                    $nextBox = $solver::solve($nextBox);
                    if($nextBox->valid() === false) break 2;
                }
                $prev_count = $next_count;
                $next_count = count($nextBox->getUnsolves());
            }

            // 矛盾があればループから抜ける
            if($nextBox->valid() === false) continue;

            // 矛盾が無ければ再帰する
            $nextBox = self::dfs($nextBox);

            // すべてが埋まったら戻る
            if($nextBox->solved()) return $nextBox;
        }

        // すべての仮定がうまくいかなかったら戻る
        return $box;
    }
}

