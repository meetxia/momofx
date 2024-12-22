<?php
require_once '../../../init.php';

if (!User::isAdmin()) {
    echo '权限不足！';
    exit;
}

if (!class_exists('EmAI', false)) {
    include __DIR__ . '/em_ai.php';
}

//处理AJAX action
$action = Input::postStrVar('action', '');
if (!isset($action)) {
    echo '操作失败，请刷新网页！';
    exit;
}

if ($action == 'generateArticle') {
    EmAI::getInstance()->chat();
}

