<?php
/**
 * 连接Beanstalkd基类
 * @author skyeinfo@qq.com
 * @LastModifyTime 2018-01-15
 * @lastModifyBy skyeinfo@qq.com
 */
include "./Client.php";
class BaseTest
{
    private static $obj;
    private static $config = array("persistent" => true, "host" => "127.0.0.1", "port" => 11300, "timeout" => 1, "logger" => null);

    public function __construct() {
        if (!isset(self::$obj)) {
            self::$obj = new Client(self::$config);
        }

        return self::$obj;
    }

    /**
     * 获取实例
     * @author: skyeinfo@qq.com
     * Last Modify Time 2018/1/20-15:43
     * Last Modify By skyeinfo@qq.com
     */
    public static function getInstance() {
        if (!isset(self::$obj)) {
            self::$obj = new Client(self::$config);
        }

        return self::$obj;
    }

    /**
     * 创建连接
     * @author: skyeinfo@qq.com
     * Last Modify Time 2018/1/20-15:44
     * Last Modify By skyeinfo@qq.com
     */
    public function connect() {
        self::$obj->connect();
    }

    /**
     * 断开连接
     * @author: skyeinfo@qq.com
     * Last Modify Time 2018/1/20-15:46
     * Last Modify By skyeinfo@qq.com
     */
    public function disconnect() {
        self::$obj->disconnect();
    }

    /**
     * for producers
     * 使用一个管道
     * @author: skyeinfo@qq.com
     * Last Modify Time 2018/1/20-15:50
     * Last Modify By skyeinfo@qq.com
     * @param $tubeName
     */
    public function useTube($tubeName){
        self::$obj->useTube($tubeName);
    }

    /**
     * 分配job
     * @author: skyeinfo@qq.com
     * Last Modify Time 2018/1/20-15:56
     * Last Modify By skyeinfo@qq.com
     * @param integer $priority 优先级 0-4294967295
     * @param integer $delay 延迟XX秒后放入队列
     * @param integer $ttr 允许worker执行job所用的时间 最小值为1
     * @param string  $data job体
     */
    public function putJob($priority, $delay, $ttr, $data) {
        self::$obj->put($priority, $delay, $ttr, $data);
    }

    /**
     * for consumer
     * 监控一个管道
     * @author: skyeinfo@qq.com
     * Last Modify Time 2018/1/20-16:02
     * Last Modify By skyeinfo@qq.com
     * @param $tubeName 管道名称
     */
    public function watch($tubeName){
        self::$obj->watch($tubeName);
    }

    /**
     * reserve a job
     * @author: skyeinfo@qq.com
     * Last Modify Time 2018/1/20-16:07
     * Last Modify By skyeinfo@qq.com
     * @param $timeout
     */
    public function reserve($timeout) {
        self::$obj->reserve($timeout);
    }

    /**
     * 删除一个job
     * @author: skyeinfo@qq.com
     * Last Modify Time 2018/1/20-16:09
     * Last Modify By skyeinfo@qq.com
     * @param $jobId
     */
    public function delete($jobId) {
        self::$obj->delete($jobId);
    }

    /**
     * 隐藏一个job
     * @author: skyeinfo@qq.com
     * Last Modify Time 2018/1/20-16:11
     * Last Modify By skyeinfo@qq.com
     * @param $jobId job_id
     * @param $priority 优先级
     */
    public function bury($jobId, $priority) {
        self::$obj->bury($jobId, $priority);
    }
}