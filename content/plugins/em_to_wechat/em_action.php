<?php
require_once '../../../init.php';

if (!User::isAdmin()) {
    echo '权限不足！';
    exit;
}

if (!class_exists('EmToWechat', false)) {
    include __DIR__ . '/em_to_wechat.php';
}

//处理AJAX action
$action = Input::getStrVar('action', '');
if (!isset($action)) {
    echo '操作失败，请刷新网页！';
    exit;
}

if ($action == 'getArticle') {
    $id = Input::getIntVar('id');
    EmToWechat::getInstance()->getContent($id);
}

