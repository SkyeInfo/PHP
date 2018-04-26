<?php

/**
 * 写入一个csv文件
 */
$csvArr = array(
    array('小明', 'man', 16),
    array('小红', 'woman', 17),
    array('小绿', 'man', 18)
);

$fh = fopen('newCSV.csv', 'w') or die('can`t open newCSV.csv');

foreach ($csvArr as $csvLine) {
    $rlt = fputcsv($fh, $csvLine);

    if ($rlt === false) {
        die('can`t write newCSV.csv');
    }
}

fclose($fh) or die('can`t close newCSV.csv');