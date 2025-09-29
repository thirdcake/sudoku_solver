<?php

require_once(dirname(__DIR__)."/vendor/autoload.php");

$start = hrtime(true);

//$problem = \Sudoku\Box\Helper::FLASH;
//$problem = \Sudoku\Box\Helper::EASY;
//$problem = \Sudoku\Box\Helper::MEDIUM;
//$problem = \Sudoku\Box\Helper::HARD;
//$problem = \Sudoku\Box\Helper::EXPERT;
$problem = \Sudoku\Box\Helper::MOST_DIFF;
$box = \Sudoku\Box\Helper::problemParse($problem);

echo $box->display();

$box = \Sudoku\Solver\Solver::solve($box);

echo $box->display();

echo intdiv(hrtime(true) - $start, 10 ** 6).PHP_EOL;

