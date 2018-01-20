<?php
/*************
 * Swoole_Http服务端
 * Http请求是短连接
 * 如果开启了KeepAlive，连接将会保持，服务器会等待下一次请求
 ************/

//实例化服务
$http_server = new Swoole\Http\Server('0.0.0.0', 9502);

//注册事件-回调函数
$http_server->on('Request', function ($request, $response){

    $tpl = file_get_contents('./test.html');

    $response->end($tpl);
});

//开启服务
$http_server->start();

