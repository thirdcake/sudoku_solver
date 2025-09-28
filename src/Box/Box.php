<?php

namespace Sudoku\Box;

class Box {
    /**
     * $row 行 $col 列の候補数をbitで管理する
     * 人間が見る数独ボードとほぼ同じ
     */
    private array $candidates;

    /**
     * 解けていないindex。
     */
    private array $unsolves;

    public function __construct() {
        $this->candidates = [];

        $this->unsolves = array_fill(0, 81, true);

        for($i=0; $i<9; $i++) {
            for($j=0; $j<9; $j++) {
                $this->candidates[$i][$j] = 0x1FF;  // 2進数で1が9個
            }
        }
    }

    /**
     * 数字を確定させる。
     */
    public function append(int $num, int $row, int $col):void {
        if($num!==0) {
            $num -= 1;  // $num は 0-index
            $block = intdiv($row, 3) * 3 + intdiv($col, 3);

            // 数の更新
            for($r=0; $r<9; $r++) {
                for($c=0; $c<9; $c++) {
                    $b = intdiv($r, 3) * 3 + intdiv($c, 3);
                    if($r===$row && $c===$col) {
                        // 更新するセル
                        $this->candidates[$r][$c] = 1 << $num;
                    }elseif($r===$row || $c===$col || $b===$block){
                        // 更新セルと同じ行、列、ブロック
                        $this->candidates[$r][$c] &= ~(1 << $num);
                    }
                }
            }

            unset($this->unsolves[$row*9+$col]);
        }
    }

    // unsolves の getter
    public function getUnsolves():array {
        return $this->unsolves;
    }

    // candidates の getter
    public function getCandidates(int $index):int {
        $row = intdiv($index, 9);
        $col = $index % 9;
        return $this->candidates[$row][$col];
    }

    // 全部のcandidates を取得
    public function getCandidatesAll():array {
        return $this->candidates;
    }

    // index 番目の candidates を消す
    public function removeCandidates(int $index, int $candidates):void {
        $row = intdiv($index, 9);
        $col = $index % 9;
        $mask = ~$candidates & 0x1FF;
        $this->candidates[$row][$col] &= $mask;
    }

    // 矛盾が発生していないか === 81マスに1つでも 0 があれば矛盾
    public function valid():bool {
        for($r=0; $r<9; $r++) {
            for($c=0; $c<9; $c++) {
                if($this->candidates[$r][$c] === 0) {
                    return false;
                }
            }
        }
        return true;
    }

    // 解けているか === unsolves が空
    public function solved():bool {
        return count($this->unsolves)===0;
    }

    // 答えを表示する文字列
    public function display():string {
        $str = '';
        if($this->solved() === true) {
            $str .= '-----SOLVED-----'.PHP_EOL;
        }else{
            $str .= '-----FAILED-----'.PHP_EOL;
        }
        foreach($this->candidates as $row => $line) {
            foreach($line as $col => $bit) {
                if(Helper::popcount($bit)===1) {
                    $str .= Helper::getNumber($bit);
                }else{
                    $str .= '0';
                }
            }
            $str .= PHP_EOL;
        }
        return $str;
    }
}

