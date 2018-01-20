<?php

/*******************
 * Swoole 进程管理模块
 *******************/

class ProcessSwoole
{
    private $workerNum = 2;//设置创建2个Work子进程

    public function createProcess()
    {
        $worker = array();
        //$funcMap = array('methodOne' , 'methodTwo' ,'methodThree' );
        for ($i = 0; $i < $this->workerNum; $i++){

            $process = new swoole_process('workerFunc');
            //$process = new swoole_process($funcMap[$i]);

            $pid = $process->start();
            echo PHP_EOL.$pid.PHP_EOL;
            $worker[$pid] = $process;
            sleep(10);
        }

        return $worker;
    }

    /**
     * 此处写回调函数是没有用的，应写到该类被引用的地方
     * worker线程启动回调函数
     * @param swoole_process $worker_process
     */
//    function worker_callback_func(swoole_process $worker_process)
//    {
//        echo 'I am '.$worker_process->pid.PHP_EOL;
//        var_dump($worker_process);
//        echo PHP_EOL;
//    }
}
?>
