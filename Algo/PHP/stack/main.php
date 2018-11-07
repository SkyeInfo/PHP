<?php
/**
 * 测试
 * @author yangshengkai@chuchujie.com
 * @lastModifyTime 2018/11/7
 * @lastModify yangshengkai@chuchujie.com
 */
namespace Stack;

require_once '../vendor/autoload.php';

$stack = new StackOnLinkedList();
$stack->push(1);
$stack->push(2);
$stack->push(3);
$stack->push(4);
$stack->push(5);
var_dump($stack->getLength());
$stack->printStack();

$stack->pop();
$stack->printStack();
$stack->pop();
$stack->printStack();
$stack->push(6);
$stack->printStack();