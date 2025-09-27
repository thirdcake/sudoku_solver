<?php

namespace Sudoku\Solver;

use Sudoku\Box\Box;

class Solver {
    private static array $solvers = [
        NakedSingle::class,
    ];
    public static function solve(Box $box):Box {
        /*
        $solvers = self::$solvers;
        foreach($solvers as $solver) {
            $box = $solver::solve($box);
        }
        
        //$box = self::dfs($box);
        */
        return $box;
    }
    /**
     * 基本的な考え方は、DFS
     * 可能性のあるものを試して、矛盾が出れば戻るを繰り返す
     * DFS の順番は、候補の少ないものから選んでいくと場合分けが減る
     * solvers が矛盾を見つけたら戻る
     */
    public static function dfs(Box $prevBox):Box {

        $box = clone $prevBox;

        // 候補の少ないものを選ぶ
        $number = $box->popMin();
        if(is_null($number)) {
            return $box;
        }

        // 候補のうち一つを入れてみて、矛盾が無ければ再帰する
        for($c=1; $c<=9; $c++) {
            // candidatesに入っていなければそもそも検討しない
            if(! $number->hasCandidate($c)) continue;

            $nextBox = clone $box;

            // solver で変更が無くなるまで繰り返す
            $solvers = self::$solvers;
            $all_count = count($solvers);
            $unchanged = 0;
            $index = 0;

            while($unchanged < $all_count) {
                $nextBox->setChanged(false);
                $nextBox = $solvers[$index]::solve($nextBox);
                $unchanged = ($nextBox->hasChanged()) ? 0 : $unchanged + 1;
                $index = ($index + 1) % $all_count;

                // 矛盾があればループから抜ける
                if(! $nextBox->valid()) {
                    break;
                }
            }

            // 矛盾があればループから抜ける
            if(! $nextBox->valid()) {
                continue;
            }

            // 矛盾が無ければ再帰する
            $nextBox = self::dfs($nextBox);

            // すべてが埋まったら戻る
            if(is_null($nextBox->popMin())) {
                return $nextBox;
            }

        }

        // すべての仮定がうまくいかなかったら戻る
        return $prevBox;
    }
}

