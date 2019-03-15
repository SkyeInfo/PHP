<?php
function reverseStr() {
    $str = 'This is a little dog!';
    //按字节反转，使用字符串方法
    print strrev($str);

    //按字节反转，不使用字符串方法1
    $newStr = '';
    $strLength = strlen($str);
    for ($i = $strLength - 1; $i >= 0; $i--) {
        $newStr .= $str[$i];
    }
    print $newStr;

    //按字节反转，不使用字符串方法2
    $strArr = str_split($str);
    $newStrOther = implode('', array_reverse($strArr));
    print $newStrOther;
}

/**
 * type：0 IPV4地址，1 IPV4地址所对应的long值
 * @author yangshengkai@chuchujie.com
 * @lastModifyTime 2019/3/14
 * @lastModify yangshengkai@chuchujie.com
 * @param int $type
 * @return mixed
 */
function getClientIp($type = 0) {
    $type = $type ? 1 : 0;
    /**
     * X_FORWARDED_FOR XFF是用来识别通过HTTP代理或负载均衡方式连接到Web服务器的客户端最原始的IP地址的HTTP请求头字段
     * 一般格式如下：X-Forwarded-For: client1, proxy1, proxy2
     * client1代表最原始的客户端ip，代理服务器每成功收到一个请求，就把请求来源IP地址添加到右边。
     * 在代理转发或反向代理中经常使用X-Forwarded-For字段。
     *
     * HTTP_CLIENT_IP 这个请求头少有，不一定服务器都实现了，客户端可以伪造。
     *
     * REMOTE_ADDR 是可靠的，它是最后一个跟服务器握手的ip，可能是用户的代理服务器，也可能是自己的反向代理，客户端不能伪造。
     *
     * 为什么会用下面这种代码获取？
     * 首先REMOTE_ADDR是客户端的ip，这个客户端指的是与请求服务器建立连接的那个端，所以有可能是用户的真实ip，
     * 也有可能是代理ip，也有可能是服务这边的反向代理的ip，这个是伪造不了的。
     *
     * X_FORWARDED_FOR是一个非正式协议，在请求转发到代理的时候代理会添加一个X-Forwarded-For头，
     * 将连接它的客户端IP（也就是用户上网机器ip）加到这个头信息里，这样末端的服务器就能获取真正上网的人的ip了。
     * 在这种情况下是可以伪造用户的真实ip的，或者隐藏掉。
     *
     * 这段生产上用的代码其实有这样的含义：
     *
     * REMOTE_ADDR 不可以显式的伪造，虽然可以通过代理将ip地址隐藏，但是这个地址仍然具有参考价值，因为它就是与你的服务器实际连接的ip地址。
     * 而HTTP_X_FORWARDED_FOR和HTTP_CLIENT_IP都是可以在header头中伪造的，
     * 生产环境中很多服务器隐藏在负载均衡节点后面，通过REMOTE_ADDR只能获取到负载均衡节点的ip地址，
     * 一般的负载均衡节点会把前端实际的ip地址通过HTTP_CLIENT_IP或者HTTP_X_FORWARDED_FOR这两种http头传递过来，
     * 后端再去读取这个值就是真实可信的，因为它是负载均衡节点告诉服务器的而不是客户端。
     * 但是当服务器直接暴露给客户端时，这两种读取方法不可信，只需要读取REMOTE_ADDR就行了
     */
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipArr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $ipArr);
        if (false !== $pos) {
            unset($ipArr[$pos]);
        }
        $ip = trim($ipArr[0]);
    } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ips = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ips[$type];
}

function getServerIp() {
    return gethostbyname(gethostname());

    //return $_SERVER['SERVER_ADDR'] ? $_SERVER['SERVER_ADDR'] : '';
}