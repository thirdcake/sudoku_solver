<?php

namespace Sudoku\Box;

/**
 * 行、列、ブロックのまとまりをbitmaskで管理する。
 * 重複を許さないので、 List より Set にしました。
 */
class NumberSet {
    private int $mask;
    private int $is_valid;

    public function __construct() {
        $this->mask = 0;
        $this->is_valid = true;
    }
    public function getMask():int {
        return $this->mask;
    }
    // $int を追加する
    public function append(int $int):void {
        if($this->contains($int)) {
            $this->is_valid = false;
        }
        $this->mask |= 1 << ($int - 1);
    }
    // $int を含んでいる場合、true を返す
    public function contains(int $int):bool {
        return ($this->mask & (1 << ($int - 1))) !== 0;
    }
    public function valid():bool {
        return $this->is_valid;
    }
}
