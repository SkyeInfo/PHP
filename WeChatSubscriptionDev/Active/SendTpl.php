<?php
/**
 *
 * @author yangshengkai@chuchujie.com
 * @lastModifyTime 2017/7/28
 * @lastModify yangshengkai@chuchujie.com
 */
require_once "./Token.php";
class SendTpl
{
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

    public function getUserOpenId($set_send_num)
    {
        $last_openid = "";
        for ($count = 0; $count <$set_send_num; $count++){
            $stime = microtime(true);
            $token = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$token."&next_openid=".$last_openid;
            $openid_info = $this->httpGet($url, "");
            $last_openid = $openid_info['next_openid'];
            file_put_contents("../log/log.txt",date("Y-m-d H:i:s",time())."last_openid=".$last_openid.PHP_EOL,FILE_APPEND);
            file_put_contents("../log/next_openid.txt", $last_openid);
            echo "last_openid================================================".$last_openid.PHP_EOL;

            /***************
             * 需要放开才能使用
             ***************/
            //$openid = $openid_info['data']['openid'];
            die;
            $this->sendTplExample($openid);

            $atime = round((microtime(true) - $stime)/1000, 3);
            echo "time================".$atime.PHP_EOL;

            $send_count = $count + 1;
            file_put_contents("./log/num.txt", $send_count.PHP_EOL);
            return true;
        }
    }

    public function sendTplExample($openid = array())
    {
        $url = '';
        $template_id = "ie39ymPkkLL-D1X7zgrzkEG3OQt1CCL6bv2BxnSyODE";
        $data = array(
            'first' => array(
                'value' => '',
                'color' => ''
            ),
            'keyword1' => array(
                'value' => '',
                'color' => ''
            ),
            'keyword2' => array(
                'value' => '',
                'color' => ''
            ),
            'keyword3' => array(
                'value' => '',
                'color' => ''
            ),
            'remark' => array(
                'value' => '',
                'color' => ''
            )

        );

        $res = $this->sendTemplate($openid, $template_id, $url, $data);
    }

    public function sendTemplate($openid, $template_id, $url, array $data)
    {
        $openid = array_chunk($openid, 200);
        $accessToken = $this->getAccessToken();
        foreach ($openid as $key => $value) {
            $res = $this->multiple_threads_request($value,$template_id,$url,$data,$accessToken);
        }
        var_dump($res);
    }

    public function multiple_threads_request($openid, $template_id, $url, $data, $accessToken)
    {
        static $suc = 0;
        static $fail = 0;

        //并行请求
        $mh = curl_multi_init();
        foreach ($openid as $op){
            $post_msg = json_encode(array(
                'touser'      => $op,
                'template_id' => $template_id,
                'url'         => $url,
                'data'        => $data
            ), JSON_UNESCAPED_UNICODE);

            $conn[$op] = curl_init();
            $url2 = 'https://api.weixin.qq.com' . '/cgi-bin/message/template/send?access_token=' . $accessToken;

            curl_setopt($conn[$op], CURLOPT_URL, $url2);
            curl_setopt($conn[$op], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($conn[$op], CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($conn[$op], CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($conn[$op], CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($conn[$op], CURLOPT_POST, true);
            curl_setopt($conn[$op], CURLOPT_POSTFIELDS, $post_msg);

            curl_multi_add_handle($mh, $conn[$op]);
        }

        do{
            curl_multi_exec($mh, $active);
        }while($active);

        foreach ($openid as $op){
            $data2 = curl_multi_getcontent($conn[$op]);
            $country_info = json_decode($data2, true);

            if ($country_info['errcode'] == 0){
                $suc++;
            }else {
                $fail++;
                $error_msg = $country_info['errmsg'].PHP_EOL;
                echo $error_msg;
            }
        }
        foreach ($openid as $op) {
            curl_multi_remove_handle($mh, $conn[$op]);
            curl_close($conn[$op]);
        };

        curl_multi_close($mh);
        $res = array(
            'success' => "成功了==================",$suc.PHP_EOL,
            'failed'  => "失败了==================".$fail.PHP_EOL
        );
        return $res;
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