<?php

/**
 * 批量更新数据 只更新一个字段
 * @param $mateArr  要分割的数据
 * @param int $len   分割的长度
 * @return array
 * @author skyeinfo@qq.com
 * @lastModifyTime 2017/10/25
 * @lastModify skyeinfo@qq.com
 */
function batchUpdate($mateArr, $len = 50) {
    $mateArrTemp = array_chunk($mateArr, $len);
    $sqlUpdate = array();
    foreach ($mateArrTemp as $k => $mateViewOrder){
        unset($mateViewOrderTemp);
        foreach ($mateViewOrder as $value){
            $mateViewOrderTemp[$value['metaId']] = $value['view_order'];
        }
        $mateIds = implode(',', array_keys($mateViewOrderTemp));
        $sql = "UPDATE `hhs_xb_polices` SET view_order = CASE material_id ";
        foreach ($mateViewOrderTemp as $mateId => $view_order){
            $sql .= sprintf("WHEN %d THEN %d ", $mateId, $view_order);
        }
        $sql .= "END WHERE material_id IN ({$mateIds})";
        $sqlUpdate[] = $sql;
    }
    return $sqlUpdate;
}

/**
 * 批量更新数据
 * @param $data  要分割的数据
 * @param $table  表名字
 * @param int $len   分割的长度
 * @return array
 * author        :skyeinfo@qq.com
 * function_name : moreUpdate
 */
function moreUpdate($data,$table,$len=50) {
	$idArray = array();
	$tmp1Array = array();
	$tmp2Array = array();
	$tmp3Array = array();
	$field1 = 'ranking';
	$field2 = 'last_ranking';
	$field3 = 'modify_time';
	$sql = array();
	$str = "UPDATE $table SET %s = CASE id
					%s
				END,
				%s = CASE id
					%s
				END,
				%s = CASE id
					%s
				END
			WHERE id IN (%s)";

	foreach($data as $key=>$val){
		$idArray[] = $key;
		$tmp1Array[] = $val[$field1];
		$tmp2Array[] = $val[$field2];
		$tmp3Array[] = $val[$field3];
	}
	$idArray = array_chunk($idArray,$len);
	$tmp1Arrays = array_chunk($tmp1Array,$len);
	$tmp2Arrays = array_chunk($tmp2Array,$len);
	$tmp3Arrays = array_chunk($tmp3Array,$len);
	foreach($tmp1Arrays as $key=>$val){
		$ids = implode(',',$idArray[$key]);
		$tmp1 = implode(' ',$tmp1Arrays[$key]);
		$tmp2 = implode(' ',$tmp2Arrays[$key]);
		$tmp3 = implode(' ',$tmp3Arrays[$key]);
		$sql[] = sprintf($str,$field1,$tmp1,$field2,$tmp2,$field3,$tmp3,$ids);
	}
	return $sql;
}