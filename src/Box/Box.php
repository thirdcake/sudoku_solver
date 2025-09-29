<?php

namespace Sudoku\Box;

class Box {
    /**
     * $row 行 $col 列の候補数をbitで管理する
     * 可能性のある数字に1を立てる。確定したら popcount 1になる
     * 人間が見る数独ボードとほぼ同じ
     */
    private array $candidates;

    /**
     * row 行、col 列、太線内 block で、確定している数字を bit で管理する
     * 確定した数字に1を立てる
     */
    private array $rows;
    private array $cols;
    private array $blocks;

    /**
     * 解けていないindex。
     */
    private array $unsolves;

    /**
     * 矛盾が生じていないか
     */
    private bool $valid;

    public function __construct() {
        $this->candidates = array_fill(0, 81, 0x1FF);

        $this->rows = array_fill(0, 9, 0);
        $this->cols = array_fill(0, 9, 0);
        $this->blocks = array_fill(0, 9, 0);

        $this->unsolves = array_fill(0, 81, true);

        $this->valid = true;

    }

    /**
     * 数字を確定させる。
     */
    public function append(int $num, int $index):void {
        if($num===0) return;

        $num -= 1;

        $row = intdiv($index, 9);
        $col = $index % 9;
        $block = intdiv($row, 3) * 3 + intdiv($col, 3);

        $bit = 1 << $num;

        $this->candidates[$index] &= $bit;
        if($this->candidates[$index]===0) {
            $this->valid = false;
        }

        // 行 更新
        $this->rows[$row] |= $bit;

        // 列 更新
        $this->cols[$col] |= $bit;

        // block 更新
        $this->blocks[$block] |= $bit;

        unset($this->unsolves[$index]);
    }

    // unsolves の getter
    public function getUnsolves():array {
        return $this->unsolves;
    }

    // 候補数のbitを返す
    public function getCandidates(int $index):int {

        $row = intdiv($index, 9);
        $col = $index % 9;
        $block = intdiv($row, 3) * 3 + intdiv($col, 3);

        $mask = $this->rows[$row] | $this->cols[$col] | $this->blocks[$block];

        return $this->candidates[$index] & ~$mask;
    }

    // 確定数字を返す
    public function digit(int $index):int {
        $row = intdiv($index, 9);
        $col = $index % 9;
        return Helper::getNumber($this->candidates[$index]);
    }

    // index 番目の候補数を消す
    public function removeCandidates(int $index, int $candidates):void {
        $row = intdiv($index, 9);
        $col = $index % 9;
        $mask = ~$candidates & 0x1FF;
        $this->candidates[$index] &= $mask;
        if($this->candidates[$index]===0) {
            $this->valid = false;
        }
    }

    // 矛盾が無いか
    public function valid():bool {
        return $this->valid;
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
        for($r=0; $r<9; $r++) {
            for($c=0; $c<9; $c++) {
                $bit = $this->candidates[$r * 9 + $c];
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

