<?php

namespace Sudoku\Box;

class Group {
    private array $row;
    private array $col;
    private array $block;

    public function __construct() {
        $this->row = array_fill(0, 9, 0);
        $this->col = array_fill(0, 9, 0);
        $this->block = array_fill(0, 9, 0);
    }
    public function append(Number $number):void {
        $bit = 1 << ($number->digit - 1);
        $this->row[$number->row] &= $bit;
        $this->col[$number->col] &= $bit;
        $this->block[$number->block] &= $bit;
    }
    public function can_append(Number $number):bool {
        if($number->digit === 0) return false;
        $bit = 1 << ($number->digit - 1);
        return (
            $this->row[$number->row] & $bit === 0
            && $this->col[$number->col] & $bit === 0
            && $this->block[$number->block] & $bit === 0
        );
    }
}
