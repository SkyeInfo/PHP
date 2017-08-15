<?php
/**
 * 获取access_token
 * @author yangshengkai@chuchujie.com
 * @lastModifyTime 2017/7/28
 * @lastModify yangshengkai@chuchujie.com
 */

/******************
 * Class Token
 * 获取access_token需要提前添加ip白名单
 * 目前其有效期是7200s
 * 待优化，初步思路是将Token存入缓存，用时先读缓存，没有再curl获取
 ******************/

class Token
{
    private $APP_ID = '';
    private $APP_SECRET = '';

    function getAccessToken(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->APP_ID."&secret=".$this->APP_SECRET;

        $tokenInfo = $this->httpGet($url);
        $tokenInfo = json_decode($tokenInfo, true);

        return $tokenInfo['data']['access_token'];
    }

    protected function httpGet($url, $data = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 200);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 500);

        $curlInfo = curl_exec($ch);

        if (curl_errno($ch)) {
            echo curl_error($ch);
            die;
        }

        curl_close($ch);

        return $curlInfo;
    }
}