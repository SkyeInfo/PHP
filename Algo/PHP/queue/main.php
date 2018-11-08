<?php
/**
 * 主方法
 * @author skyeinfo@qq.com
 * @lastModifyTime 2018/11/8
 * @lastModify skyeinfo@qq.com
 */
namespace Queue;

require_once '../vendor/autoload.php';

$queue = new QueueOnLinkedList();
$queue->enqueue(6);
$queue->enqueue(5);
$queue->enqueue(4);
$queue->enqueue(3);
$queue->enqueue(2);
$queue->enqueue(1);
$queue->printQueue();

$queue->dequeue();
$queue->printQueue();
$queue->dequeue();
$queue->dequeue();
$queue->printQueue();
