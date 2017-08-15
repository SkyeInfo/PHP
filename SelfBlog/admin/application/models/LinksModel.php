<?php
/**
 * 链接管理模型
 * @author yangshengkai
 * @time 2017/04/11
 */
class LinksModel extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取全部链接
     * @return array
     */
    public function getLinks()
    {
        $this->db->select('*');
        $this->db->from('links');
        $this->db->order_by('id', 'ASC');
        $rlt = $this->db->get()->result_array();
        $rlt[] = array(
            'id' => 100,
            'url' => 'http://www.skyeinfo.com',
            'bloger' => 'Skye-杨胜凯',
            'description' => '我的个人博客',
            'type' => 'tech'
        );
        return $rlt;
    }

    /**
     * 添加链接
     * @param $data
     */
    public function insertLink($data = array())
    {
        if(!empty($data)) {
            $rlt = $this->db->insert('links', $data);
            return $rlt;
        }
    }

    /**
     * 获取单个链接的信息
     * @param $id
     * @return mixed
     */
    public function getLink($id)
    {
        $this->db->where('id', $id);
        $this->db->from('links');
        $rlt = $this->db->get()->row_array();

        return $rlt;
    }

    /**
     * 更新链接信息
     * @param array $data
     * @return bool
     */
    public function updateLink($data = array())
    {
        if(!empty($data)) {

            $this->db->where('id', $data['id']);
            $rlt = $this->db->update('links', $data);

            return $rlt;
        }else {
            return false;
        }
    }

    /**
     * 删除链接
     * @param array $ids
     * @return mixed
     */
    public function deleteLinks($ids = array())
    {
        $this->db->where_in('id', $ids);
        $result = $this->db->delete('links');

        return $result;
    }

}