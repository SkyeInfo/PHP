<?php
require_once "./sendMsg.php";
require_once "./sendTpl.php";

class MySocket{


    public $sendMsg;
    public $sendTpl;
    public $process;
    public $instance;
    public $fd;
    public $params;
    public $pid;
    public $media;

    /**
     * 开启Swoole-Server服务
     * @author yangshengkai@chuchujie.com
     * @lastModifyTime ${DATE}
     * @lastModify yangshengkai@chuchujie.com
     */
    public function start()
    {
        $serv = new Swoole\Websocket\Server("0.0.0.0", 9503);
        $this->sendMsg  = new SendMsg();
        $this->sendTpl  = new SendTpl();
        $this->instance = $serv;
        if (!isset($this->process)){
            $this->createChildProcess($serv);
        }
        $serv->addProcess($this->process);
        $this->responseRequest();
        $serv->start();
    }

    /**
     * 创建swoole子进程
     * 闭包函数
     * @author yangshengkai@chuchujie.com
     * @lastModifyTime ${DATE}
     * @lastModify yangshengkai@chuchujie.com
     */
    public function createChildProcess($serv)
    {
        //此处process做为回调函数值传入
        $this->process = new swoole_process(function ($process) use ($serv){
            file_put_contents("./pid.txt", $process->pid);
            swoole_process::signal(SIGCHLD, function ($sig) {
                while ($ret = swoole_process::wait(false)){
                    //非阻塞模式
                    echo "PID={$ret['pid']}\n";
                }
            });

            while (true){
                $data = $this->process->read();
                $data_decode = json_decode($data, true);
                $flag = $data_decode['flag'];
                echo $flag.PHP_EOL;

                if($flag == "sendMsg"){
                    $this->sendMsg->sendAllMsg($data_decode, $serv);
                    $flag = 'off';
                }elseif ($flag == "sendTpl"){
                    $num = $data_decode['set_send_num'];
                    $res = $this->sendTpl->getUserOpenId($num, $serv);
                    $flag = 'off';
                }
            }
        });
    }

    public function responseRequest()
    {
        $this->instance->on('Open', function ($server, $req){
            echo "Connection open:".$req->fd;
        });

        $this->instance->on('Message', function ($server, $frame) {
            echo "Message: " . $frame->data . PHP_EOL;
            $this->params = json_decode($frame->data, true);
            file_put_contents("../log/log.txt", date("Y-m-d H:i:s", time()) . $frame->data . PHP_EOL, FILE_APPEND);
            $this->instance = $server;
            $this->fd = $frame->fd;
            $method = $this->params['flag'];
            $this->{$method}();
        });
            $this->instance->on('Close', function ($server, $fd){
            echo "Connection closed:".$fd;
        });
    }

    protected function getMedia()
    {
        $media = $this->sendMsg->getMedia();
        $this->instance->push($this->fd, json_encode($media));
    }

    protected function sendMsg()
    {
        if (!isset($this0->process)) {
            $this->createChildProcess();
        }
        $this->process->write(json_encode($this->params));
    }

    protected function sendTpl()
    {
        if (!isset($this->process)) {
            $this->createChildProcess();
        }
        $this->process->write(json_encode($this->params));
    }
    protected function getProcess()
    {
        $num = file_get_contents("../log/num.txt");
        $this->instance->push($this->fd, json_encode(intval($num)));
    }

    protected function close()
    {
        $pid = intval(file_get_contents("../log/pid.txt"));
        $this->process->kill($pid);
        foreach ($this->instance->connections as $connection) {
            $this->instance->close($connection, false);
        }
    }

    protected function reset()
    {
        file_put_contents("../log/next_openid.txt", "");
        file_put_contents("../log/num.txt", "");
        $this->instance->push($this->fd, "重置成功");
    }

}