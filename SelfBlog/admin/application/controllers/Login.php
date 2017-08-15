<?php
/**
 * 登陆控制器
 * @author yangshengkai
 * @time 2017/05/07
 */

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->library('HttpAuthClass');
    }

    public function index()
    {
        //检查是否已登录
        if($this->checkLogin()) {
            redirect('home');
        }else {
            $this->load->view('login');
        }
    }

    /**
     * 验证账户
     */
    public function checkAcc()
    {
        $method = $this->input->method();
		
        //防止恶意攻击
        if('post' == $method) {
            //获取post的数据
            $data_post = $this->input->post();
            if(array_key_exists("UserName", $data_post) && array_key_exists("Password", $data_post)) {
                //检查是否存在此用户，并获取用户信息
                $rlt = $this->checkUser($data_post['UserName']);
                if($rlt) {
                    $user = $rlt;

                    $isVaild = $this->checkPwd($data_post['Password'], $user['password']);

                    if($isVaild) {
                        //记录cookie和session
                        /**
                         * 注意 引用的类库用小写，如下
                         */
                        $this->httpauthclass->setLoginInfo($data_post['UserName']);

                        $this->session->unset_userdata('msg');

                        redirect('home');
                    }else {
                        $this->session->set_userdata('msg','密码错误');

                        $this->load->view('login');
                    }
                }else {
                    $this->session->set_userdata('msg','该用户不存在');

                    $this->load->view('login');
                }
            }else {
                $this->session->set_userdata('msg', '不合法输入');

                $this->load->view('login');
            }
        }else {
            $this->session->unset_userdata('msg');
            $this->load->view('login');
        }
    }

    /**
     * 登出
     */
    public function logout()
    {
        $this->session->sess_destroy();
        $this->httpauthclass->setLogoutInfo();
        redirect('login');
    }

    /**
     * 校验密码
     * @param $pwd 用户输入的密码
     * @param $md5_pwd 库里存的md5加密后的密码
     * @return bool
     */
    protected function checkPwd($pwd, $md5_pwd)
    {
        if (md5($pwd) === $md5_pwd) {
            return true;
        }else {
            return false;
        }
    }

    /**
     * 检查用户是否存在 存在则返回用户信息，否则返回FALSE
     * @param $username 用户名
     * @return mixed
     */
    protected function checkUser($username)
    {
        $userinfo = $this->UserModel->getUser(array('username' => $username));

        if(count($userinfo) !== 0 && !is_null($userinfo)) {
            return $userinfo;
        }else {
            return false;
        }
    }

    /**
     * 检查是否已经登录
     * @return bool
     */
    protected function checkLogin()
    {
        if($this->session->has_userdata('LogStatus') && ($this->session->LogStatus === 'login') ) {
            return true;
        }else {
            return false;
        }
    }

}