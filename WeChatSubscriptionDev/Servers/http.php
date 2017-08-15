<?php
/**
 * HTTPServer
 */
$serv = new Swoole\Http\Server("0.0.0.0", 9502);
$serv->on("Request", function ($request, $response) {
    if ($request->server['request_uri'] == "/wechat/1"){
        $string = file_get_contents("./sendMsg.html");
        $response->end($string);
    }elseif ($request->server['request_uri'] == "/wechat/2"){
        $string = file_get_contents("./sendTpl.html");
        $response->end($string);
    }
});

$serv->start();
?>