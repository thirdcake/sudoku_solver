<?php

namespace Sudoku\Box;

class Number {
    private int $candidates;
    private int $row;
    private int $col;
    private int $block;
    private int $digit;
    public function __construct(
        int $number,
        int $row = 0,
        int $col = 0
    ) {
        if($number === 0) {
            $this->candidates = (1 << 9) - 1;
            $this->digit = 0;
        }else{
            $this->candidates = 1 << ($number - 1);
            $this->digit = $number;
        }
        $this->row = $row;
        $this->col = $col;
        $this->block = intdiv($row, 3) * 3 + intdiv($col, 3);
    }
    public function candidateCount():int {
        return POPCOUNT[$this->candidates];
    }
    // maskとの突合を消す
    public function eliminate(int $mask):void {
        $this->candidates &= ~$mask;
    }
    public function valid():bool {
        return $this->candidates > 0;
    }
    public function getRCB():array {
        return [$this->row, $this->col, $this->block];
    }
    public function getDigit():int {
        if (($this->digit > 0) && (POPCOUNT[$this->candidates] === 1)) {
            $this->digit = [
                1 => 1,
                2 => 2,
                4 => 3,
                8 => 4,
                16 => 5,
                32 => 6,
                64 => 7,
                128 => 8,
                256 => 9,
            ][$this->candidates];
        }
        return $this->digit;
    }
}
