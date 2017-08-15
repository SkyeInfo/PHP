<?php
/**
 * 用户管理模型
 * @author yangshengkai
 * @time 2017/02/24
 */

class UserModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取全部用户
     * @param bool $uid
     * @return mixed
     */
    public function getUser($uid = false)
    {
        if(is_numeric($uid)) {

            $query = $this->db->get_where('userinfo', array('uid' => $uid));
            return $query->row_array();

        }else if(is_array($uid)) {

            $query = $this->db->get_where('userinfo', (array)$uid);

            return $query->row_array();
        } else {
            $this->db->order_by('uid');
            $this->db->from('userinfo');
            $query = $this->db->get()->result_array();

            return $query;
        }
    }

    /**
     * 添加用户
     * @param $data
     * @return mixed
     */
    public function insertUser($data)
    {
        if($data !== null) {
            $rlt = $this->db->insert('userinfo', $data);
            return $rlt;
        }
    }

    /**
     * 查询用户
     * @param $data
     * @return mixed
     */
    public function queryUser($data)
    {
        $this->db->where(array('name'=>$data['inputEmail'],'password'=>$data['inputPassword']));
        $this->db->from('userinfo');
        return $this->db->count_all_results();
    }

    /**
     * 更新用户信息
     * @param $data
     * @return bool
     */
    public function updateUser($data)
    {
        if(!is_null($data))
        {
            $this->db->where('uid',$data['uid']);
            $this->db->update('userinfo',$data);

            return true;
        }
        return false;
    }

    /**
     * 删除用户
     * @param array $ids
     * @return bool|int
     */
    public function deleteUsers($ids = array()){
        if (!empty($ids)){
            $this->db->where_in('uid', $ids);
            $result = $this->db->delete('userinfo');

            if ($result){
                return true;
            }else{
                return false;
            }
        }else{
            return 0;
        }
    }

}