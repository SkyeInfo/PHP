<?php
/**
 *
 * @author yangshengkai@chuchujie.com
 * @lastModifyTime 2017/8/25
 * @lastModify yangshengkai@chuchujie.com
 */
//$mateJson = file_get_contents('hhs_xb_polices.json');hhs_xb_material_goods.json
$mateJson = file_get_contents('hhs_xb_material_goods.json');
$mateArr = explode(',', $mateJson);
$mateId = array();
foreach ($mateArr as $value){
    $a = (array)json_decode($value);
    foreach ($a as $v){
        $mateId[] = (int)$v;
    }
}
$mateId = array_unique($mateId);
$str = implode(',', $mateId);

var_dump($str);
