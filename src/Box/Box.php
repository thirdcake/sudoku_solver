<?php

namespace Sudoku\Box;

class Box {
    private array $grid;
    private array $unsolves;  // まだ確定していない Number のリスト
    private array $rows;
    private array $cols;
    private array $blocks;
    private bool $changed;

    public function __construct(string $problem) {
        $this->grid = [];
        $this->unsolves = [];
        $this->parse($problem);

        $this->rows = [];
        $this->cols = [];
        $this->blocks = [];

        for($i=0; $i<9; $i++) {
            $this->rows[] = new NumberSet();
            $this->cols[] = new NumberSet();
            $this->blocks[] = new NumberSet();
        }
        for($r=0; $r<9; $r++) {
            for($c=0; $c<9; $c++) {
                if(is_int($this->grid[$r][$c])) {
                    $b = intdiv($r, 3) * 3 + intdiv($c, 3);
                    $this->rows[$r]->append($this->grid[$r][$c]);
                    $this->cols[$c]->append($this->grid[$r][$c]);
                    $this->blocks[$b]->append($this->grid[$r][$c]);
                }
            }
        }

        $this->changed = false;
    }
    private function parse(string $problem):void {
        $grid = [];
        $unsolves = [];
        $pp = explode(PHP_EOL, $problem);
        foreach($pp as $row=>$v) {
            $line = trim($v);
            $len = strlen($line);
            for($col=0; $col<$len; $col++) {
                $num = (int) $line[$col];
                $number = new Number($num, $row, $col);
                if($number->getDigit()===0) {
                    $grid[$row][$col] = $number;
                    $unsolves[] = $number;
                }else{
                    $grid[$row][$col] = $number->getDigit();
                }
            }
        }
        $this->grid = $grid;
        $this->unsolves = $unsolves;
    }
    public function popMin():Number|null {
        if(empty($this->unsolved)) {  // 未解明が無ければnull
            return null;
        }
        
        // candidateCount の大きい順に並べて、最後の要素をpopする
        // unsolved には、popされたnumberは残らない
        ursort($this->unsolved, fn($a, $b) =>
            $a->candidateCount() <=> $b->candidateCount()
        );

        return array_pop($this->unsolved);
    }
    public function setChanged(bool $bool):void {
        $this->changed = $bool;
    }
    public function hasChanged():bool {
        return $this->changed;
    }
    public function placeNumber(Number $number):void {
        [$r, $c, $b] = $number->getRCB();
        $digit = $number->getDigit();
        $this->grid[$r][$c] = $digit;
        $this->rows[$r]->append($digit);
        $this->setChanged(true);
    }
    public function getUnsolves():array {
        return $this->unsolves;
    }
    public function setUnsolves(array $unsolves):void {
        $this->unsolves = $unsolves;
    }
    public function valid():bool {
        $flag = true;
        foreach($this->unsolves as $number) {
            if($number->valid() === false) {
                $flag = false;
            }
        }
        foreach($this->rows as $group) {
            if($group->valid() === false) {
                $flag = false;
            }
        }
        foreach($this->cols as $group) {
            if($group->valid() === false) {
                $flag = false;
            }
        }
        foreach($this->blocks as $group) {
            if($group->valid() === false) {
                $flag = false;
            }
        }
        return $flag;
    }
    public function getMask(int $r, int $c, int $b):int {
        $row = $this->rows[$r]->getMask();
        $col = $this->cols[$c]->getMask();
        $block = $this->blocks[$b]->getMask();
        return $row | $col | $block;
    }
    public function solved():bool {
        return true;
    }
    public function display():string {
        $disp = '';
        foreach($this->grid as $line) {
            $vals = [];
            foreach($line as $v) {
                $vals[] = is_int($v) ? $v : '?';
            }
            $disp .= implode('', $vals).PHP_EOL;
        }
        return $disp;
    }
    // test 用
    public function dumpNumber(int $r, int $c):int {
        if(is_int($this->grid[$r][$c])) return $this->grid[$r][$c];
        return -100;
    }
    public function __clone():void {
        foreach($this->unsolves as $i => $number) {
            $this->unsolves[$i] = clone $number;
        }
        foreach($this->rows as $i => $row) {
            $this->rows[$i] = clone $row;
        }
        foreach($this->cols as $i => $col) {
            $this->cols[$i] = clone $col;
        }
        foreach($this->blocks as $i => $block) {
            $this->blocks[$i] = clone $block;
        }
    }
}

