<?php
/**
 * php-7.1.9
 * mc扩展-3.0.9
 * mcd扩展-3.0.3
 */
//mc_config
$config = array(
    'host'   => '127.0.0.1',
    'port'   => 11211,
    'weight' => 1
);

$mc  = new MemCache();
$mcd = new Memcached();

$mc->addServer($config['host'], $config['port'], true, $config['weight']);
$mcd->addServer($config['host'], $config['port'], $config['weight']);

//用mc写入，用mcd读取
$goodInfo = array(
    'good_id'   => 1,
    'good_name' => "ikbc机械键盘",
    'price'     => 100
);

$mc->set('good_info', json_encode($goodInfo), 0, 86400);

$good = $mcd->get('good_info');

echo "用mc写入，用mcd读取" . PHP_EOL;
var_dump(json_decode($good, true));

//用mcd写入，用mc读取
$orderInfo = array(
    'order_id' => 1,
    'order_sn' => "20180204235400abc",
    'good_id'  => 1234,
    'money'    => 100
);
$mcd->set('order_info', json_encode($orderInfo), 86400);

$order = $mc->get('order_info');

echo "用mcd写入，用mc读取" . PHP_EOL;
var_dump(json_decode($order, true));

$mc->close();
$mcd->quit();

