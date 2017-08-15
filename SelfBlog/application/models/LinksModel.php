<?php
/**
 * Links模型
 * @author yangshengkai
 * @time 2017/04/11
 */
class LinksModel extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取链接数量
     * @return mixed
     */
    public function getLinksNum()
    {
        $this->db->from('links');
        $num = $this->db->count_all_results();

        return $num;
    }

    /**
     * 获取链接
     * @param array $limit
     * @return array
     */
    public function getLinks($limit = array('num' => false,'offset' => false))
    {
        $this->db->select('*');
        $this->db->from('links');
        $this->db->limit($limit['num'], $limit['offset']);
        $rlt = $this->db->get()->result_array();
        return $rlt;
    }

}