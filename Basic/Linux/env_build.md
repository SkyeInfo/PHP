pass 
skyeinfo

虚拟机环境下

-桥接模式-
开放端口
/sbin/iptables -I INPUT -p tcp --dport 80 -j ACCEPT
/sbin/iptables -I INPUT -p tcp --dport 8080 -j ACCEPT
/sbin/iptables -I INPUT -p tcp --dport 22 -j ACCEPT
/sbin/iptables -I INPUT -p tcp --dport 3306 -j ACCEPT

然后保存：
/etc/rc.d/init.d/iptables save
 
查看打开的端口：
/etc/init.d/iptables status
第一步配置网络，更改相应配置文件，在VB平台上采用桥接模式，配置防火墙 放开22端口等

yum安装wget
yum install wget

cd /etc/yum.repos.d/
mv CentOS-Base.repo CentOS-Base.repo_bak
wget -O /etc/yum.repos.d/CentOS-Base.repo http://mirrors.163.com/.help/CentOS7-Base-163.repo
yum clean all
yum makecache

yum更新，

安装gcc
安装gcc-c++

下载php源码包

源码安装php
直接在安装路径下删除掉整个文件即可

安装相关依赖
yum install bzip2-devel curl-devel db4-devel libjpeg-devel libpng-devel libXpm-devel gmp-devel libc-client-devel openldap-devel sqlite-devel aspell-devel net-snmp-devel libxslt-devel libxml2-devel pcre-devel  mysql-devel unixODBC-devel postgresql-devel net-snmp-devel libxslt-devel libtidy-devel

------

./configure --prefix=/home/skyeinfo/web/php --exec-prefix=/home/skyeinfo/web/php --with-mysql=mysqlnd --with-mysqli=mysqlnd --with-pdo-mysql=mysqlnd --with-iconv-dir --with-jpeg-dir --with-png-dir --with-zlib --with-libxml-dir --enable-xml --disable-rpath --enable-bcmath --enable-shmop --enable-sysvsem --enable-inline-optimization --with-curl --with-curlwrappers --enable-mbregex --enable-fpm --enable-cli --enable-mbstring --with-gd --enable-gd-native-ttf --with-openssl --with-mhash --enable-pcntl --enable-sockets --with-xmlrpc --enable-zip --enable-soap --without-pear --with-freetype-dir=/usr/local/include/freetype2

make 
make test
make install

-配置php.ini 源码目录文件
cp php.ini-development /home/skyeinfo/web/php/etc/php.ini

-添加环境变量
export PATH=$PATH:/home/skyeinfo/web/php/bin

-php7
-加上
/home/skyeinfo/web/php/etc/php-fpm.d/www.conf
cp /home/skyeinfo/web/php/etc/php-fpm.d/www.conf.default /home/skyeinfo/web/php/etc/php-fpm.d/www.conf

-php-fpm 服务
cp /home/skyeinfo/web/php/etc/php-fpm.conf.default /home/skyeinfo/web/php/etc/php-fpm.conf
cp sapi/fpm/init.d.php-fpm /etc/init.d/php-fpm
chmod +x /etc/init.d/php-fpm

chkconfig --add php-fpm

-启动 php-fpm
service php-fpm start
Starting php-fpm  done

-编译php扩展
/home/skyeinfo/web/php/bin/phpize
./configure --with-php-config=/home/skyeinfo/web/php/bin/php-config
make
make test
make install

-centos-7配置防火墙
firewall-cmd --zone=public --add-port=80/tcp --permanent
firewall-cmd --reload


nginx yum安装
rpm -ivh http://nginx.org/packages/centos/6/noarch/RPMS/nginx-release-centos-6-0.el6.ngx.noarch.rpm
yum info nginx
yum -y install nginx
service nginx start
ps -ef|grep nginx

nginx源码安装
wget http://nginx.org/download/nginx-1.12.2.tar.gz
tar -zxvf nginx-1.12.2.tar.gz
yum -y install gcc pcre pcre-devel zlib zlib-devel openssl openssl-devel

./configure --prefix=/home/skyeinfo/web/nginx --sbin-path=/home/skyeinfo/web/nginx

make 
make install


yum安装mysql
http://www.centoscn.com/mysql/2014/1219/4335.html
之后
http://www.cnblogs.com/xiaoluo501395377/archive/2013/04/07/3003278.html


Redis
http://blog.csdn.net/ludonqin/article/details/47211109

启动命令
/usr/local/bin/redis-server /etc/redis/redis.conf


VB-共享文件夹
yum install -y gcc gcc-devel gcc-c++ gcc-c++-devel make kernel kernel-devel
挂载增强工具镜像
/media
mkdir /mnt/cdrom
mount -t auto -r /dev/cdrom /mnt/cdrom/
cd /mnt/cdrom/
./VBoxLinuxAdditions.run
shutdown -r now

mount -t vboxsf productgroup /home/skyeinfo/www/
cd /home/skyeinfo/www/

在文件 /etc/rc.local 中（用root用户）追加如下命令实现自动挂载
mount -t vboxsf productgroup /home/skyeinfo/www/
