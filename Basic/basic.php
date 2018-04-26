<?php

reverseStr();

function reverseStr() {
    $str = 'This is a little dog!';
    //按字节反转，使用字符串方法
    print strrev($str);

    //按字节反转，不使用字符串方法1
    $newStr = '';
    $strLength = strlen($str);
    for ($i = $strLength - 1; $i >= 0; $i--) {
        $newStr .= $str[$i];
    }
    print $newStr;

    //按字节反转，不使用字符串方法2
    $strArr = str_split($str);
    $newStrOther = implode('', array_reverse($strArr));
    print $newStrOther;
}

