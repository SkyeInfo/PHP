### 基于Yii的日志可视化工具

Elasticsearch

####1.使用时需要修改config->web.php 

```
'elasticsearch' => [
    'class' => 'yii\elasticsearch\Connection',
    'nodes' => [
        ['http_address' => '127.0.0.1:9200'],//修改为ES地址
                
        // configure more hosts if you have a cluster
    ],
],
```
####2.简单的ELK日志分析系统搭建

[elasticsearch+logstash+kibana](https://www.elastic.co/cn/products)
  
具体的搭建过程可以在网络中搜索教程，如果都采用最新的版本（5.0+）的话可能会有些问题，大家可以参考下面我给出的链接解决。

[Elasticsearch5.0 安装问题集锦](http://www.cnblogs.com/sloveling/p/elasticsearch.html)