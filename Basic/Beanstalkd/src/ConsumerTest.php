<?php
/**
 * 消费者模型
 * @author skyeinfo@qq.com
 * @LastModifyTime 2018-01-15
 * @lastModifyBy skyeinfo@qq.com
 */
include "./BaseTest.php";

$consumer = new Client();

$consumer->connect();

$consumer->watch("test");

while (true) {
    $job = $consumer->reserve();

    $result = json_encode($job);

    if ($result){
        echo $result;
        $consumer->delete($job["id"]);
    } else {
        $consumer->bury($job["id"]);
    }
}
?>