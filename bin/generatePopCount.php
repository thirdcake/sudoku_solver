<?php

// 0 から 511 までの pop count をあらかじめ計算しておくと速いはず。
// gmp_popcount が使える環境なら、その方が早いかもしれない。
// OPCache が効くはず。
// 利用時は、require_once で、array が return される。

function popcount(int $num):int {
    $cnt = 0;
    for($i=0; $i<9; $i++) {
        $cnt += ($num & (1 << $i)) > 0 ? 1 : 0;
    }
    return $cnt;
}

$pop_counts = [];
for($i=0; $i<(1 << 9); $i++) {
    $pop_counts[$i] = popcount($i);
}

$outFileName = dirname(__DIR__) . '/src/Box/popcount.php';
$str = '<?php'.PHP_EOL.'// php ./bin/generatePopCount.php'.PHP_EOL;
$str .= 'return '.var_export($pop_counts, true);
$str .= ';'.PHP_EOL;

if(file_put_contents($outFileName, $str) === false) {
    echo "file put error".PHP_EOL;
    exit();
}
echo "Wrote {$outFileName}".PHP_EOL;