<?php
/**
 * 测试
 * @author skyeinfo@qq.com
 * @lastModifyTime 2018/11/5
 * @lastModify skyeinfo@qq.com
 */
namespace LinkedList;

require_once '../vendor/autoload.php';

use LinkedList\SingleLinkedList;

$a = new SingleLinkedListAlgo();
$a->testAlgo();

class SingleLinkedListAlgo
{
    public $list;

    public function __construct(SingleLinkedList $list = null) {
        $this->list = $list;
    }

    /**
     * 设置链表
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/6
     * @lastModify skyeinfo@qq.com
     * @param \LinkedList\SingleLinkedList $list
     */
    public function setList(SingleLinkedList $list) {
        $this->list = $list;
    }

    /**
     * 反转单链表 无环
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/6
     * @lastModify skyeinfo@qq.com
     * @return bool
     */
    public function reverse() {
        if (null == $this->list || null == $this->list->head || null == $this->list->head->next) {
            return false;
        }

        $preNode = null;
        $curNode = $this->list->head->next;
        $remainNode = null;

        $headNode = $this->list->head;
        $this->list->head->next = null;

        while ($curNode != null) {
            $remainNode = $curNode->next;
            $curNode->next = $preNode;
            $preNode = $curNode;
            $curNode = $remainNode;
        }

        $headNode->next = $preNode;

        return true;
    }

    /**
     * 用快慢指针判断链表是否有环
     * 原理解释：http://t.cn/ROxpgQ1
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/6
     * @lastModify skyeinfo@qq.com
     * @return bool
     */
    public function checkCircle() {
        if (null == $this->list || null == $this->list->head || null == $this->list->head->next) {
            return false;
        }

        //将两个节点都指向第一个节点
        $slow = $this->list->head->next;
        $fast = $this->list->head->next;

        while ($fast != null && $fast->next != null) {
            $fast = $fast->next->next;
            $slow = $slow->next;

            if ($slow === $fast) {
                return true;
            }
        }

        return false;
    }

    public function testAlgo() {
        $linkedList = new SingleLinkedList();
        $linkedList->insert(1);
        $linkedList->insert(2);
        $linkedList->insert(3);
        $linkedList->insert(4);
        $linkedList->insert(5);
        $linkedList->insert(6);
        $linkedList->insert(7);
        $listAlgo = new SingleLinkedListAlgo($linkedList);
        $listAlgo->list->printList();
        $listAlgo->reverse();
        $listAlgo->list->printList();


        $listCircle = new SingleLinkedList();
        $listCircle->buildHasCircleList();
        $listAlgo->setList($listCircle);
        var_dump($listAlgo->checkCircle());

    }
}