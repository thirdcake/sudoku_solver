<?php

namespace Sudoku\Box;

use Sudoku\Box\Box;

class Helper {
    public static function problemParse(string $problem):Box {
        $box = new Box();
        $pp = preg_split('/\n/', $problem);
        foreach($pp as $row => $x) {
            $line = str_split(trim($x));
            foreach($line as $col => $num) {
                $num = (int) $num;
                $box->append($num, $row, $col);
            }
        }
        return $box;
    }
}

