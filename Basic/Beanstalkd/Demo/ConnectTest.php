<?php
/**
 * 连接Beanstalkd
 * @author skyeinfo@qq.com
 * @LastModifyTime 2018-01-15
 * @lastModifyBy skyeinfo@qq.com
 */
namespace Demo;

class ConnectTest
{
    private static $obj;
    private static $config = array("persistent" => true, "host" => "127.0.0.1", "port" => 11300, "timeout" => 1, "logger" => null);

    public function __construct() {}

    public static function getInstance() {
        if (!isset(self::$obj)) {
            self::$obj = new Client(self::$config);
        }

        return self::$obj;
    }

    /**
     * 写入消息队列
     * @author skyeinfo@qq.com
     * @LastModifyTime 2018-01-15
     * @lastModifyBy skyeinfo@qq.com
     */
    public function writeQueue() {

    }

    /**
     * 读取消息队列
     * @author skyeinfo@qq.com
     * @LastModifyTime 2018-01-15
     * @lastModifyBy skyeinfo@qq.com
     */
    public function readQueue() {

    }
}