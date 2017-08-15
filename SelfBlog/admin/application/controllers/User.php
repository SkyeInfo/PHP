<?php
/**
 * 用户管理控制器
 * @author yangshengkai
 * @time 2017/02/26
 */

class User extends MY_Controller
{
    var $_pwd;
    public function __construct ()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->model('UserModel');
        $data = array(
            'users' => $this->UserModel->getUser()
        );

        if(is_null($data['users'])) {
            //todo 这里加载新建用户的界面，虽然这种情况不一定会出现
        } else {
            $this->load->view('user/user_list',$data);
        }
    }

    /**
     * 创建用户
     */
    public function create()
    {
        $this->load->view('user/user_create');
    }
    public function addUser()
    {
        $this->load->model('UserModel');
        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'username', 'trim|required|min_length[5]|max_length[12]',
            array(
                'required' => '必须填写用户名!',
                'min_length' => '不得少于5个字符！',
                'max_length' => '不得多于12个字符'
            )
        );
        $this->form_validation->set_rules('mail', 'mail', 'valid_email|required',
            array(
                'required' => '必须填写邮箱地址!',
                'valid_email' => '邮箱格式不对!'
            )
        );
        $this->form_validation->set_rules('pwd', '密码','trim|required|callback_getPwd',
            array(
                'required' => '必须填写密码!',
            )
        );
        $this->form_validation->set_rules('pwd-ack', '密码确认','required|callback_pwdAckCheck',
            array(
                'required' => '必须确认密码!'
            )
        );

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = $this->input->get_post();

            $data_insert['username'] = $data['username'];
            $data_insert['password'] = md5($data['pwd']);
            $data_insert['uname'] = $data['uname'];
            $data_insert['mail'] = $data['mail'];
            $data_insert['createtime'] = date('Y-m-d G:i:s');
            $data_insert['log'] = $data['log'];

            $ack = $this->UserModel->insertUser($data_insert);
            if($ack) {
                redirect('user');
            } else {
                echo '<script>alert("用户添加失败");</script>';
            }

        }
    }

    /**
     * 加载用户编辑界面
     */
    public function editUser()
    {
        $this->load->model('UserModel');
        $uid = $this->uri->segment(3);

        $userinfo = $this->UserModel->getUser($uid);
        if(!is_null($userinfo)) {
            $data = array(
                'user' => array(
                    'uid' => $userinfo['uid'],
                    'username' => $userinfo['username'],
                    'mail' => $userinfo['mail'],
                    'uname' => $userinfo['uname'],
                    'log' => $userinfo['log']
                )
            );
            $this->load->view('user/user_edit', $data);
        } else {
           redirect('user');
        }
    }

    /**
     * 更新用户
     * @param $uid
     */
    public function updateUser($uid)
    {
        $this->load->model('UserModel');

        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        $this->form_validation->set_rules('mail', 'mail', 'valid_email|required',
            array(
                'required' => '必须填写邮箱地址!',
                'valid_email' => '邮箱格式不对!'
            )
        );
        $this->form_validation->set_rules('oldpwd', '原密码','trim|required',
            array(
                'required' => '必须填写旧密码!',
            )
        );
        $this->form_validation->set_rules('pwd', '新密码','trim|required|callback_getPwd',
            array(
                'required' => '必须填写新密码!',
            )
        );
        $this->form_validation->set_rules('pwd-ack', '密码确认','required|callback_pwdAckCheck',
            array(
                'required' => '必须确认新密码!'
            )
        );

        if ($this->form_validation->run() == false) {
            $this->editUser();
        } else {
            $data = $this->input->post();
            $userinfo = $this->UserModel->getUser($uid);
            $oldpwd = $userinfo['password'];
            if ($oldpwd == md5($data['oldpwd'])) {

                $data_update['uid'] = $uid;
                $data_update['password'] = md5($data['pwd']);
                $data_update['uname'] = $data['uname'];
                $data_update['mail'] = $data['mail'];
                $ack = $this->UserModel->updateUser($data_update);
                if ($ack) {
                    redirect('user');
                }else {
                    echo '<script>alert("用户信息修改失败");</script>';
                }
            }else {
                echo '<script>alert("原密码输入错误");</script>';
                $this->editUser();
            }
        }
    }

    /**
     * 删除用户
     */
    public function delUser(){
        $ids = $this->input->get_post('ids');

        $this->load->model('UserModel');
        $rlt = $this->UserModel->deleteUsers($ids);

        if ($rlt === true){
            $data['msg'] = 'Delete Success';
            $this->renderOutput($data, 0 , 'Delete Success', true);
        }else if ($rlt === false){
            $data['msg'] = 'Delete Success';
            $this->renderOutput($data, 1 , 'Delete Fail', true);
        }else{
            $data['msg'] = 'Delete Error';
            $this->renderOutput($data, 2 , 'Data Error', true);
        }

    }

    /**
     * 校验密码
     * @param $str
     */
    public function getPwd($str)
    {
        $this->_pwd = $str;
    }
    public function pwdAckCheck($str)
    {
        if($str != $this->_pwd) {
            $this->form_validation->set_message('pwdAckCheck', '两次输入的密码不一致');
            return false;
        }else {
            return true;
        }
    }

}