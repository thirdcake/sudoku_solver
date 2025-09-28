<?php

namespace Sudoku\Box;

use Sudoku\Box\Box;

class Helper {

    private static ?array $popcount_array = null;

    public const FLASH = <<<EOL
    072900306
    059806721
    681020054
    538492600
    200310040
    107008003
    810639570
    000100409
    005274030
    EOL;
    public const EASY = <<<EOL
    380040000
    560009020
    020083000
    010300400
    070064000
    050102000
    092531704
    807026951
    100897060
    EOL;
    public const MEDIUM = <<<EOL
    050970802
    070603450
    040000300
    100006070
    020000900
    005801000
    008000000
    060732080
    910000206
    EOL;
    public const HARD = <<<EOL
    500040000
    004007360
    620580400
    800000020
    007031000
    950004803
    000000086
    000010047
    000050000
    EOL;
    public const EXPERT = <<<EOL
    006900800
    004060290
    300000000
    040000706
    700030000
    060020085
    200000600
    009500000 
    005340000
    EOL;
    public const MOST_DIFF = <<<EOL
    800000000
    003600000
    070090200
    050007000
    000045700
    000100030
    001000068
    008500010
    090000400
    EOL;

    // popcount を返す
    public static function popcount(int $num):int {
        if(self::$popcount_array === null) {
            self::$popcount_array = require __DIR__.'/popcount.php';
        }
        return self::$popcount_array[$num];
    }

    // popcount が1の数字を返す
    public static function getNumber(int $bit):int {
        return [
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
    }

    // 問題の文字列を parse
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

