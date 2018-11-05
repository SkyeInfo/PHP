<?php
/**
 * 主程序
 * @author yangshengkai@chuchujie.com
 * @lastModifyTime 2018/11/5
 * @lastModify yangshengkai@chuchujie.com
 */
namespace LinkedList;

use LinkedList\SingleLinkedList;

require_once '../vendor/autoload.php';

/**
 * 检查链表是否是回文串
 * @author yangshengkai@chuchujie.com
 * @lastModifyTime 2018/11/5
 * @lastModify yangshengkai@chuchujie.com
 * @param SingleLinkedList $list
 * @return bool
 */
function isPalindrome(SingleLinkedList $list) {
    if ($list->getLength() <= 1) {
        return true;
    }

    $pre = null;
    $slow = $list->head->next;
    $fast = $list->head->next;
    $remindNode = null;

    while ($fast != null && $fast->next != null) {
        $fast = $fast->next->next;

        $remindNode = $slow->next;
        $slow->next = $pre;
        $pre = $slow;
        $slow = $remindNode;
    }

    if ($fast != null) {
        $slow = $slow->next;
    }

    while ($slow != null) {
        if ($slow->data != $pre->data) {
            return false;
        }

        $slow = $slow->next;
        $pre = $pre->next;
    }

    return true;
}
$list = new SingleLinkedList();
$list->insert('a');
$list->insert('b');
$list->insert('c');
$list->insert('c');
$list->insert('b');
$list->insert('a');
var_dump(isPalindrome($list));