<?php
/**
 * 标签模型
 * @author yangshengkai
 * @time 2017/04/15
 */
class TagsModel extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取标签
     * @return array
     */
    public function getTags()
    {
        $this->db->select('mid, pmid, name, postcount');
        $this->db->from('metas');
        $this->db->where('mid !=', 1);

        $rlt = $this->db->get()->result_array();
        if (empty($rlt)){
            $rlt[] = array(
                'mid' => 2,
                'pmid' => 0,
                'name' => '技术',
                'postcount' => 0
            );
            $rlt[] = array(
                'mid' => 3,
                'pmid' => 0,
                'name' => '生活',
                'postcount' => 0
            );
        }
        return $rlt;
    }

}