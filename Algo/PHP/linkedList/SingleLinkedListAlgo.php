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

    /**
     * 合并两个有序递增链表
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/7
     * @lastModify skyeinfo@qq.com
     * @param \LinkedList\SingleLinkedList $listA
     * @param \LinkedList\SingleLinkedList $listB
     * @return \LinkedList\SingleLinkedList
     */
    public function mergerSortedList(SingleLinkedList $listA, SingleLinkedList $listB) {
        if (null == $listA) {
            return $listB;
        }

        if (null == $listB) {
            return $listA;
        }

        $pListA = $listA->head->next;
        $pListB = $listB->head->next;

        $newList = new SingleLinkedList();
        $newHead = $newList->head;

        $newRootNode = $newHead;

        while ($pListA != null && $pListB != null) {
            if ($pListA->data <= $pListB->data) {
                $newRootNode->next = $pListA;
                $pListA = $pListA->next;
            } else {
                $newRootNode->next = $pListB;
                $pListB = $pListB->next;
            }

            $newRootNode = $newRootNode->next;
        }

        if ($pListA != null) {
            $newRootNode->next = $pListA;
        }

        if ($pListB != null) {
            $newRootNode->next = $pListB;
        }

        return $newList;
    }

    /**
     * 删除链表的倒数第N个节点
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/7
     * @lastModify skyeinfo@qq.com
     * @param $index
     * @return bool
     */
    public function deleteLastKth($index) {
        if (null == $this->list || null == $this->list->head || null == $this->list->head->next || $index <= 0) {
            return false;
        }
        $i = 1;

        $slow = $this->list->head;
        $fast = $this->list->head;

        while ($fast != null && $i < $index) {
            $fast = $fast->next;
            $i++;
        }

        if ($fast == null || $fast->next == null) {   //index已经超出单链表长度
            return true;
        }

        $pre = null;
        while ($fast->next != null) {
            $pre = $slow;
            $slow = $slow->next;
            $fast = $fast->next;
        }

        $pre->next = $pre->next->next;

        return true;
    }

    /**
     * 寻找中间节点
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/7
     * @lastModify skyeinfo@qq.com
     * @return bool
     */
    public function findMiddleNode() {
        if (null == $this->list || null == $this->list->head || null == $this->list->head->next) {
            return false;
        }

        $slow = $this->list->head->next;
        $fast = $this->list->head->next;

        while ($fast != null && $fast->next != null) {
            $fast = $fast->next->next;
            $slow = $slow->next;
        }

        return $slow;
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
        $linkedList->insert(8);
        $listAlgo = new SingleLinkedListAlgo($linkedList);
        $listAlgo->list->printList();
        $listAlgo->reverse();
        $listAlgo->list->printList();


        $listCircle = new SingleLinkedList();
        $listCircle->buildHasCircleList();
        $listAlgo->setList($listCircle);
        var_dump($listAlgo->checkCircle());

        // 两个有序的链表合并
        $listA = new SingleLinkedList();
        $listA->insert(9);
        $listA->insert(7);
        $listA->insert(5);
        $listA->insert(3);
        $listA->insert(1);
        $listA->printList();

        $listB = new SingleLinkedList();
        $listB->insert(10);
        $listB->insert(8);
        $listB->insert(6);
        $listB->insert(4);
        $listB->insert(2);
        $listB->printList();

        $listAlgoMerge = new SingleLinkedListAlgo();
        $newList = $listAlgoMerge->mergerSortedList($listA, $listB);
        $newList->printListSimple();

        $listDelete = new SingleLinkedList();
        $listDelete->insert(1);
        $listDelete->insert(2);
        $listDelete->insert(3);
        $listDelete->insert(4);
        $listDelete->insert(5);
        $listDelete->insert(6);
        $listDelete->insert(7);
        $listDelete->printList();
        $listAlgo->setList($listDelete);
        $listAlgo->deleteLastKth(6);
        $listAlgo->list->printListSimple();

        // 求链表的中间结点
        $listAlgo->setList($linkedList);
        $middleNode = $listAlgo->findMiddleNode();
        var_dump($middleNode->data);
    }
}