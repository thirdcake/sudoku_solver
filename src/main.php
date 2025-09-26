<?php

require_once(dirname(__DIR__)."/vendor/autoload.php");

// flash
$problem = <<<EOL
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
// most difficult
/*
$problem = <<<EOL
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
*/
define('POPCOUNT', include __DIR__."/Box/popcount.php");

$box = new \Sudoku\Box\Box($problem);

$box = \Sudoku\Solver\Solver::solve($box);

if($box->solved()) {
    echo PHP_EOL."------SOLVED------".PHP_EOL;
}else{
    echo PHP_EOL."------FAILED------".PHP_EOL;
}

echo $box->display();


