<?php

/**
 * Returns num-th sentence of given dataset
 */

$file = 'datasets/' . $ds;

if ($num > intval(exec("wc -l '$file'"))) {
    echo "sentence does not exists!";
    return;
}


if (!file_exists($file))
    echo 'ERROR: invalid dataset!';
else {
    $spl = new SplFileObject($file);
    $spl->seek($num);
    echo $spl->current();
}
?>