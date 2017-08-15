<?php

require_once "./Token.php";
class SendMsg {
    /**
     * 获取令牌
     * @author yangshengkai@chuchujie.com
     * @lastModifyTime 2017/07/28
     * @lastModify yangshengkai@chuchujie.com
     * @return int|mixed
     */
    public function getAccessToken()
    {
        $token = new Token();
        $accessToken = $token->getAccessToken();

        return $accessToken;
    }

    /**
     * 获取素材信息，限定每次拉取5条
     * @author yangshengkai@chuchujie.com
     * @lastModifyTime ${DATE}
     * @lastModify yangshengkai@chuchujie.com
     */
    public function getMedia()
    {
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$token;
        $data = json_encode(array('type' => 'news','offset' => 0, 'count' => 5));
        $res = $this->httpPost($url, $data);
        $data = array();
        foreach ($res['item'] as $key => $val){
            $data[$key]['media_id'] = $val['media_id'];
            $data[$key]['title']    = $val['content']['news_item'][0]['title'];
        }

        return $data;
    }

    /**
     * 发送图文消息
     * @author yangshengkai@chuchujie.com
     * @lastModifyTime ${DATE}
     * @lastModify yangshengkai@chuchujie.com
     * @param array $data
     * @return bool
     */
    public function sendAllMsg($data = array())
    {
        if (empty($data)) {
            echo "no params";
            return false;
        }

        $media_id     = $data['media_id'];
        $set_send_num = $data['set_send_num'];
        $next_openid  = $data['next_openid'];
        $token = $this->getAccessToken();
        for ($i = 0; $i < $set_send_num; $i++) {
            $openid = $this->getOpenidInfo($token, $next_openid);
            $post_data = json_encode(array(
                //'touser' => $openid['data']['openid'],
                'touser' => array(
                    'oioKYt9N9IRnpK_c5BdyR82pFvHA',
                    'oioKYt5YpJpWDk_uIxXnAYEIbHD4'
                ),

                'mpnews' => array(
                    'media_id' => $media_id
                ),
                'msgtype' => 'mpnews'
            ));


            $url_news = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$token;
            $res = $this->httpPost($url_news, $post_data);

            $result = json_decode($res);

            if ($result['errcode'] == 0){

            }

        }
    }

    /**
     * 获取10000条openid
     * @author yangshengkai@chuchujie.com
     * @lastModifyTime ${DATE}
     * @lastModify yangshengkai@chuchujie.com
     * @param $token
     * @param $next_openid
     * @return bool|mixed|string
     */
    public function getOpenidInfo($token, $next_openid)
    {
        if (!isset($token)){
            return false;
        }

        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$token."&next_openid=".$next_openid;
        $openid = $this->httpGet($url);
        if (empty($openid['data']['openid'])){
            echo "get no openid";
            return false;
        }

        return $openid;
    }

    /**
     * cURL Get请求
     * @author yangshengkai@chuchujie.com
     * @lastModifyTime ${DATE}
     * @lastModify yangshengkai@chuchujie.com
     * @param $url
     * @param null $data
     * @return mixed|string
     */
    public function httpGet($url, $data = null)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $tmpInfo = curl_exec($ch);

        $res = json_decode($tmpInfo,TRUE);

        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        curl_close($ch);
        return $res;
    }

    /**
     * cURL Post请求
     * @author yangshengkai@chuchujie.com
     * @lastModifyTime ${DATE}
     * @lastModify yangshengkai@chuchujie.com
     * @param $url
     * @param null $data
     * @return mixed|string
     */
    public function httpPost($url, $data = null)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $res = curl_exec($ch);
        $res = json_decode($res, true);

        if (curl_errno($ch)) {
            $msg = curl_error($ch);
            return $msg;
        }

        curl_close($ch);
        return $res;
    }

}