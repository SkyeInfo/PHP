### 关于memcache扩展和memcached扩展的一个小测试

>关于MC缓存中mc和mcd的区别可以参考https://www.jianshu.com/p/749f8bebef80

做这个测试原因是最近因为项目要迁服务器，在测试新服务器环境时遇到了新老服务器中php的mc扩展用的不一致问题，
新服务器中mc扩展和mcd扩展都有，老服务器中只有mc扩展，同时因为一些未知原因，mc在项目中被应用的有点膈应，
具体原因就不说了，都是泪-_-，考虑到以后灰度测试以及可能要升php7，mc扩展已经不受支持了，而且性能不如mcd扩展好，然后
就对这一方面做了整改，以后项目里只用mcd扩展，同时整理了一下老代码，这样就又把mc用了起来。

测试的主要内容是从应用上（不研究源码实现）看mc扩展和mcd扩展混用会不会有什么问题

```php
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
```

结果：可以混用

![](http://p2zl7fc12.bkt.clouddn.com/memcache.png)