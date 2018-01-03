<?php
/**
 * 多curl并发请求基础脚本-POST
 * @author skyeinfo@qq.com
 * @Last Modify Time 2017/12/28
 * @param $requestArr
 */
function multiCurl($requestArr) {

    if(empty($requestArr)){
        return;
    }
    $value1 = $value2 = $value3 = "";

    //初始化multi_curl
    $mh = curl_multi_init();

    //构造句柄
    foreach($requestArr as $item) {

        // TODO Something

        $postData = json_encode(array(
            "value1" => $value1,
            "value2" => $value2,
            "value3" => $value3
        ), JSON_UNESCAPED_UNICODE);

        //初始化-设置单个curl
        $conn[$item] = curl_init();
        $url = "url";
        curl_setopt($conn[$item], CURLOPT_URL, $url);
        curl_setopt($conn[$item], CURLOPT_RETURNTRANSFER, true);
        curl_setopt($conn[$item], CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($conn[$item], CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($conn[$item], CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($conn[$item], CURLOPT_POST, true);
        curl_setopt($conn[$item], CURLOPT_POSTFIELDS, $postData);

        //加入句柄
        curl_multi_add_handle($mh, $conn[$item]);
    }

    //执行多curl
    do {
        curl_multi_exec($mh, $active);
    } while ($active);

    //处理curl日志，关闭并移除单个curl
    foreach ($requestArr as $value){
        $rltStr = curl_multi_getcontent($conn[$value]);
        $res = json_decode($rltStr, true);

        if ($res['code'] == 0) {
            // TODO Something
        }else {
            // TODO Something
        };

        curl_close($conn[$value]);
        curl_multi_remove_handle($mh,$conn[$value]);
    }

    //关闭多curl
    curl_multi_close($mh);
}