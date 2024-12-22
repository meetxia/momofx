<?php
defined('EMLOG_ROOT') || exit('access denied!');

// 开启插件时执行该函数
function callback_init() {
    // do something
}

// 删除插件时执行该函数
function callback_rm() {

    $storage = Storage::getInstance('nciaer_weixin_share');
    $storage->deleteAllName('YES');
}

// 更新插件时执行该函数
function callback_up() {
    // do something
}
