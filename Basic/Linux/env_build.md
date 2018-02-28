VirtualBox虚拟机环境下CentOS6.x版本

（网络设置）

-桥接模式-

开放端口，保存

>/sbin/iptables -I INPUT -p tcp --dport 80 -j ACCEPT

>/sbin/iptables -I INPUT -p tcp --dport 8080 -j ACCEPT

>/sbin/iptables -I INPUT -p tcp --dport 22 -j ACCEPT

>/sbin/iptables -I INPUT -p tcp --dport 3306 -j ACCEPT

>/etc/rc.d/init.d/iptables save
 
查看打开的端口：

>/etc/init.d/iptables status

（修改yum源）

先忍痛安装一下wget

>yum install wget

（修改yum源）

>cd /etc/yum.repos.d/

>mv CentOS-Base.repo CentOS-Base.repo_bak

>wget -O /etc/yum.repos.d/CentOS-Base.repo http://mirrors.163.com/.help/CentOS7-Base-163.repo

>yum clean all

>yum makecache


yum安装gcc
yum安装gcc-c++

（源码安装php7）

下载php源码包

安装相关依赖

>yum install bzip2-devel curl-devel db4-devel libjpeg-devel libpng-devel libXpm-devel gmp-devel libc-client-devel openldap-devel sqlite-devel aspell-devel net-snmp-devel libxslt-devel libxml2-devel pcre-devel  mysql-devel unixODBC-devel postgresql-devel net-snmp-devel libxslt-devel libtidy-devel

cd进入解压后的php源码文件夹中
执行（--prefix和--exec-prefix指安装路径，根据自己的需求更改）
>./configure --prefix=/home/skyeinfo/web/php --exec-prefix=/home/skyeinfo/web/php --with-mysql=mysqlnd --with-mysqli=mysqlnd --with-pdo-mysql=mysqlnd --with-iconv-dir --with-jpeg-dir --with-png-dir --with-zlib --with-libxml-dir --enable-xml --disable-rpath --enable-bcmath --enable-shmop --enable-sysvsem --enable-inline-optimization --with-curl --with-curlwrappers --enable-mbregex --enable-fpm --enable-cli --enable-mbstring --with-gd --enable-gd-native-ttf --with-openssl --with-mhash --enable-pcntl --enable-sockets --with-xmlrpc --enable-zip --enable-soap --without-pear --with-freetype-dir=/usr/local/include/freetype2

>make
 
>make test

>make install

-配置php.ini 源码目录文件

>cp php.ini-development /home/skyeinfo/web/php/etc/php.ini

-添加环境变量

>export PATH=$PATH:/home/skyeinfo/web/php/bin

-添加php-fpm配置文件
>cp /home/skyeinfo/web/php/etc/php-fpm.d/www.conf.default /home/skyeinfo/web/php/etc/php-fpm.d/www.conf

-配置php-fpm 服务

>cp /home/skyeinfo/web/php/etc/php-fpm.conf.default /home/skyeinfo/web/php/etc/php-fpm.conf

>cp sapi/fpm/init.d.php-fpm /etc/init.d/php-fpm

>chmod +x /etc/init.d/php-fpm

-添加开机自启动

>chkconfig --add php-fpm

-启动 php-fpm

>service php-fpm start

-源码编译、安装php扩展之一般流程

>/home/skyeinfo/web/php/bin/phpize

>./configure --with-php-config=/home/skyeinfo/web/php/bin/php-config

>make

>make test

>make install

-centos-7配置防火墙

>firewall-cmd --zone=public --add-port=80/tcp --permanent
>firewall-cmd --reload


（nginx yum安装）

>rpm -ivh http://nginx.org/packages/centos/6/noarch/RPMS/nginx-release-centos-6-0.el6.ngx.noarch.rpm

>yum info nginx

>yum -y install nginx

>service nginx start

>ps -ef|grep nginx

（nginx源码安装）

>wget http://nginx.org/download/nginx-1.12.2.tar.gz

>tar -zxvf nginx-1.12.2.tar.gz

>yum -y install gcc pcre pcre-devel zlib zlib-devel openssl openssl-devel

>./configure --prefix=/home/skyeinfo/web/nginx --sbin-path=/home/skyeinfo/web/nginx

>make 

>make install


yum安装mysql（参考网址教程）
>
http://www.centoscn.com/mysql/2014/1219/4335.html

之后

http://www.cnblogs.com/xiaoluo501395377/archive/2013/04/07/3003278.html


Redis安装

http://blog.csdn.net/ludonqin/article/details/47211109

启动Redis命令

/usr/local/bin/redis-server /etc/redis/redis.conf


VirtualBox-共享文件夹

>yum install -y gcc gcc-devel gcc-c++ gcc-c++-devel make kernel kernel-devel

挂载增强工具镜像

>/media

>mkdir /mnt/cdrom

>mount -t auto -r /dev/cdrom /mnt/cdrom/

>cd /mnt/cdrom/

>./VBoxLinuxAdditions.run

>shutdown -r now

>mount -t vboxsf productgroup /home/skyeinfo/www/   (此处路径自己设置，“productgroup” 是在VirtualBox端设置的)

>cd /home/skyeinfo/www/

在文件 /etc/rc.local 中（用root用户）追加如下命令实现自动挂载

>mount -t vboxsf productgroup /home/skyeinfo/www/
