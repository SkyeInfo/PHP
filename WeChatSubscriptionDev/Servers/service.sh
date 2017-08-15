#/bin/bash
http_pid=http.pid
socket_pid=socket.pid

start() {
    if [-e $http_pid];then
        echo "already running"
    else
        #添加配置php路径
        php http.php & echo $! > $http_pid
        php socket.php & echo $! > $socket_pid

        echo "running"
        exit 1
    fi
}
stop() {
    if [-e $http_pid];then
        kill 'cat $http_pid'
        kill 'cat $socket_pid'
        rm -f $http_pid
        rm -f $socket_pid
    else
        echo "Not Running"
    fi
}

case "$1" in
start)
    start
    ;;
stop)
    stop
    ;;
restart)
    stop
    sleep 1
    start
    ;;
*)
    echo $"Usage: {start|stop|restart}"
    exit 1
    ;;
esac
exit 0

