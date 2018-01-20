<?php

require_once "./SwooleProcess.php";

//实例化服务
$ws_server = new Swoole\Websocket\Server('0.0.0.0', 9503);

//注册监听事件-回调函数
$ws_server->on('open', function ($server, $req){
    echo 'connection open:'.$req->fd.PHP_EOL;
    //$fd是TCP客户端连接的标识符，在Server程序中是唯一的,是一个自增数字，范围是 1 ～ 1600万
});

//$frame 包含了客户端发来的数据帧信息
$ws_server->on('message', function ($server, $frame) {
    echo "Receive from [{$frame->fd}] ,[{$frame->data}]".PHP_EOL;
    $receive_msg = $frame->data;
    $receive_msg = json_decode($receive_msg, true);

    if ($receive_msg['msg'] == 1){
        $server->push($frame->fd, 'Hello');

    }else if($receive_msg['msg'] == 2){
        $server->push($frame->fd, "Skye");

    }else if($receive_msg['msg'] == 3){
        $server->push($frame->fd, "YangSK");

    }else if($receive_msg['msg'] == 'process'){
        $swoole_process = new ProcessSwoole();
        $worker = $swoole_process->createProcess();

        $server->push($frame->fd, json_encode($worker));

        foreach($worker as $pid => $process){// $process 是子进程的句柄
            $process->write("hello worker[$pid]\n");//子进程句柄向自己管道里写内容                  $process->write($data);
            echo "From Worker: ".$process->read();//子进程句柄从自己的管道里面读取信息    $process->read();
            echo PHP_EOL.PHP_EOL;
        }

    }else{
        $server->push($frame->fd, "不知道你在说什么");
    }

});

$ws_server->on('close', function ($server, $fd){
    echo "Client-{$fd}-Closed".PHP_EOL;
});
$ws_server->start();

function workerFunc(swoole_process $worker){//这里是子进程哦

    /**
     * 在分析这个问题的时候注意以下几点
     * 1.read()方法在读取的时候是阻塞的，没有可读信息就一直等
     * 2.这是主线程 和 子线程在通信，是两个线程
     */
    $recv = $worker->read();
    echo PHP_EOL. "From Master: $recv\n";
    //send data to master
    sleep(10);
    $worker->write("hello master , this pipe  is ". $worker->pipe .";  this  pid  is ".$worker->pid."\n");
    sleep(10);
    $worker->exit(0);
}

/**
 * worker线程启动回调函数
 * @param swoole_process $worker_process
 */
function worker_callback_func(swoole_process $worker_process)
{
    echo 'I am '.$worker_process->pid.PHP_EOL;
    var_dump($worker_process);
    echo PHP_EOL;
}

function methodOne(swoole_process $worker){// 第一个处理
    echo $worker->callback .PHP_EOL;
}

function methodTwo(swoole_process $worker){// 第二个处理
    echo $worker->callback .PHP_EOL;
}

function methodThree(swoole_process $worker){// 第三个处理
    echo $worker->callback .PHP_EOL;
}
