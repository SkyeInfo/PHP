<?php
//set_error_handler(function ($error_no, $error_msg, $error_file, $error_line){echo $error_no. ' ' . $error_msg;}, E_ALL | E_STRICT);
error_reporting(E_ALL & ~E_NOTICE);
//register_shutdown_function(function (){ echo 'a1q';var_dump(error_get_last()); }
$a = array(
    1 => 'a',
    2 => 'b',
    3 => 'c',
    4 => 'd'
);

foreach ($a as $k => &$v) {
    if ($k == 3) {
        $v = 'e';
    }
}
var_dump($a);

foreach ($a as $k => $v) {
    echo $v;
}
var_dump($a);

