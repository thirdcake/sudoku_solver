<?php

namespace Sudoku\Box;

class Number {
    private int $candidates;
    private bool $updated;
    private bool $original;
    private int $row;
    private int $col;
    private int $digit = 0;
    public function __construct(
        int $candidates,
        bool $updated = false,
        bool $original = false,
        int $row = 0,
        int $col = 0
    ) {
        if($candidates === 0) {
            $this->candidates = (1 << 9) - 1;
        }else{
            $this->candidates = $candidates;
            if($candidates & ($candidates - 1) === 0) {
                // $candidates!==0 のとき pop_count===1 と同じ
                for($i=1; $i<=9; $i++) {
                    if($candidates & (1<<($i-1)) > 1) {
                        $this->digit = $i;
                    }
                }
            }
        }
        $this->updated = $updated;
        $this->original = $original;
        $this->row = $row;
        $this->col = $col;
    }
    public function isOriginal() {
        return $this->original;
    }
}
