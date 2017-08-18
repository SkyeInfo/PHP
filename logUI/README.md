### 基于Yii的日志可视化工具

Elasticsearch

1.使用时需要修改config->web.php 

```
'elasticsearch' => [
    'class' => 'yii\elasticsearch\Connection',
    'nodes' => [
        ['http_address' => '127.0.0.1:9200'],//修改为ES地址
                
        // configure more hosts if you have a cluster
    ],
],
```