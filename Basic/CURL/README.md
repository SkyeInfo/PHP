### PHP-CURL

curl是Linux中的常用命令

在PHP中经常会使用cURL库来处理一些HTTP请求。

对于一些大批量的HTTP请求我们可以用PHP自带的curl_multi_*方法来进行并发请求以节省时间。
>multiCurl.php中即为一个基本的curl并发请求方法
