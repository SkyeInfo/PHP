Nginx 转发与负载均衡
---------

利用Nginx实现反向代理，依据转发规则将请求转发到对应的web服务器上。

> 反向代理：反向代理（Reverse Proxy）方式是指以代理服务器来接受internet上的连接请求，然后将请求转发给内部网络上的服务器，并将从服务器上得到的结果返回给internet上请求连接的客户端，此时代理服务器对外就表现为一个反向代理服务器。

在nginx.conf中进行配置，如下例：

* url转发：


    http {
        server {
            server_name example.com;
    
            location /good/ {
                proxy_pass http://example.com:port_good/;
            }
    
            location /order/ {
                proxy_pass http://example.com:port_order/;
            }
    
            location / {
                proxy_pass http://example.com:port_default/;
            }
            
        }
    }
已上示例中会
> 将http://example.com/good/ 下的请求转发到 http://example.com:port_good/

> 将http://example.com/order/ 下的请求转发到 http://example.com:port_order/

> 将其它所有请求转发到 http://example.com:port_default/

值得注意的是：当代理服务器地址中是含有url的，则url会替换掉location所匹配的url部分，而如果代理服务器地址中是不带url的，则会用完整的请求url来转发到代理服务器。

例如：
http://example.com/good/index.html   ===>   http://example.com:port_good/index.html
http://example.com/shop/goods/list   ===>   http://example.com:port_default/shop/goods/list