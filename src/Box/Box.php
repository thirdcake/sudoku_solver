<?php

namespace Sudoku\Box;

class Box {
    private array $grid;
    public function __construct(string $problem) {
        $this->grid = [];
    }
    private function parse(string $problem):void {
    }
    public function solved():bool {
    }
    public function display():string {
    }
}

