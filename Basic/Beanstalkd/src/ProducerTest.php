<?php
/**
 * 生产者模型
 * @author skyeinfo@qq.com
 * @LastModifyTime 2018-01-15
 * @lastModifyBy skyeinfo@qq.com
 */
include "./BaseTest.php";

$producer = new BaseTest();

//创建一个连接
$producer->connect();

//创建一个管道
$tube = "test";
$producer->useTube($tube);

//放置一个job
$producer->putJob(1,0,60,"file/path");

//断开连接
$producer->disconnect();