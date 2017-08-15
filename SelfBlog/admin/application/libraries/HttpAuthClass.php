<?php
/**
 * User: yangshengkai
 * Time: 2017/02/24
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class HttpAuthClass {
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('cookie');
        $this->CI->load->library('session');
    }

    public function isUserValid($obj)
    {
        if($obj) {
            $obj = $this->unserializeUserData($obj);

            if(is_array($obj)) {
                if(isset($obj['logintime']) and isset($obj['username']) and isset($obj['keepalivetime']) and isset($obj['status'])) {
                    $nowTime = time();

                    if($nowTime > ($obj['logintime'] + $obj['keepalivetime'])) {
                        return json_encode(array(
                            'msg'=>urlencode('超过登录保持时间'),
                            'flag'=> false
                        ));
                    } else {
                        return $this->isUserOk($obj['username'], $obj['status']);
                    }
                } else {
                    return json_encode(array(
                        'msg'=>urlencode('cookie中缺少指定信息'),
                        'flag'=> false
                    ));
                }
            } else {
                return json_encode(array(
                    'msg' => urlencode('cookie信息格式错误'),
                    'flag' => false
                ));
            }
        } else {
            return json_encode(array(
                'msg' => urlencode( '未取得指定cookie信息'),
                'flag' => false
            ));
        }
    }

    public function setLoginInfo($username)
    {
        $time = time();

        $cookieInfo = array(
            'logintime' => $time,
            'username' => $username,
            'keepalivetime' => 86400,
            'status' => true
        );

        $sessionInfo = array(
            'Lastlogintime' => $time,
            'status' => true
        );

        //设置cookie
        $infoStr = $this->serializeUserData($cookieInfo);
        set_cookie('skye_content', $infoStr, 86400);

        //设置session
        $infoStr = $this->serializeUserData($sessionInfo);

        $this->CI->session->set_userdata($username, $infoStr);
        $this->CI->session->mark_as_temp('keepalivetime', 86400);
        $this->CI->session->set_userdata('LoginUser', $username);
        $this->CI->session->set_userdata('LogStatus', 'login');
    }

    protected function isUserOk($username, $status)
    {
        $obj = $this->CI->session->userdata($username);

        if($obj) {
            $obj = $this->unserializeUserData($obj);
            if($obj['status'] == $status) {

                return json_encode(array(
                    'msg'=> urlencode('用户验证成功'),
                    'flag'=> true
                ));
            } else {
                return json_encode(array(
                    'msg'=> urlencode('当前用户登录已经失效'),
                    'flag'=> false
                ));
            }
        } else {
            return json_encode(array(
                'msg' => urlencode( '服务器未查询到当前用户的登录信息'),
                'flag' => false
            ));
        }
    }

    public function setLogoutInfo()
    {
        set_cookie('skye_content','',0);
    }

    /**
     * 序列化用户信息，待存入cookie
     * @param $obj
     * @return string
     */
    public function serializeUserData($obj)
    {
        $info = base64_encode(serialize($obj));
        return $info;
    }

    public function showdebug()
    {
        echo 'Http验证类库';
    }

    /**
     * 反序列化cookie信息，转成用户信息
     * @param $obj
     * @return mixed
     */
    public  function unserializeUserData($obj)
    {
        $info = unserialize(base64_decode($obj));
        return $info;
    }

}