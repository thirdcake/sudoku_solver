<?php

namespace Sudoku\Box;

class Box {
    /**
     * $row 行 $col 列の候補数をbitで管理する
     * 人間が見る数独ボードとほぼ同じ
     */
    private array $grid;

    /**
     * 数字 $num-1 が、行・列・ブロックの何番目の候補になっているかをbitで管理する
     * $rows[9-1][2] = 110111111; のとき、2行目、3列目に9は入らない
     * 人間が見る数独ボードとは違う見た目だが、 hidden single などを解くために必要
     * 数字が、管理の都合上 0-indexであることに注意が必要
     */
    private array $rows;
    private array $cols;
    private array $blocks;

    /**
     * 解けていないindex。
     */
    private array $unsolves;

    public function __construct() {
        $this->grid = [];

        $this->rows = [];
        $this->cols = [];
        $this->blocks = [];
        
        $this->unsolves = [];

        for($i=0; $i<9; $i++) {
            for($j=0; $j<9; $j++) {
                $this->grid[$i][$j] = (1 << 9) - 1;
                $this->rows[$i][$j] = (1 << 9) - 1;
                $this->cols[$i][$j] = (1 << 9) - 1;
                $this->blocks[$i][$j] = (1 << 9) - 1;
                $this->unsolves[$i*9+$j] = true;
            }
        }
    }

    /**
     * 数字を確定させる。
     * append しただけでは、同じ行、列などに波及されないことに注意。
     */
    public function append(int $num, int $row, int $col):void {
        if($num!==0) {
            $this->grid[$row][$col] = 1 << ($num - 1);
            $this->rows[$num-1][$row] = 1 << $col;
            $this->cols[$num-1][$col] = 1 << $row;

            $block = intdiv($row, 3) * 3 + intdiv($col, 3);
            $block_index = ($row % 3) * 3 + ($col % 3);
            $this->blocks[$num-1][$block] = 1 << $block_index;
            
            unset($this->unsolves[$row*9+$col]);
        }
    }

    // 矛盾が発生していないか === 81マスに1つでも popcount 0 があれば矛盾
    public function valid():bool {
        for($r=0; $r<9; $r++) {
            for($c=0; $c<9; $c++) {
                if(POPCOUNT[$this->grid[$r][$c]]===0) {
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
        foreach($this->grid as $row => $line) {
            foreach($line as $col => $bit) {
                if(POPCOUNT[$bit]===1) {
                    $str .= [
                        1 => 1,
                        2 => 2,
                        4 => 3,
                        8 => 4,
                        16 => 5,
                        32 => 6,
                        64 => 7,
                        128 => 8,
                        256 => 9,
                    ][$bit];
                }else{
                    $str .= '0';
                }
            }
            $str .= PHP_EOL;
        }
        return $str;
    }
}

