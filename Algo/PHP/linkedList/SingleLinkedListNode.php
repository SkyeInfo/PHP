<?php
/**
 * 链接节点类
 * @author skyeinfo@qq.com
 * @lastModifyTime 2018/10/23
 * @lastModify skyeinfo@qq.com
 */
namespace LinkedList;

class SingleLinkedListNode
{
    //节点存储的值
    public $data;

    //节点中存储的下一个节点的地址
    public $next;

    public function __construct($data = null) {
        $this->data = $data;
        $this->next = null;   //默认为null
    }
}