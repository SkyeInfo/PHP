<?php
/**
 * User: yangshengkai
 * Time: 2017/02/24
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if(!$this->checkLogin()) {
            redirect('login');
        }
    }

    /**
     * 检查是否已登录
     * @return bool
     */
    protected function checkLogin()
    {
        $this->load->library('HttpAuthClass');
        $cookie = get_cookie('skye_content');

        $val_cookie = $this->httpauthclass->isUserValid($cookie);
        $rlt_json = json_decode($val_cookie, true);
        if($rlt_json['flag']) {
            return true;
        } else {
            return false;
        }
    }

}