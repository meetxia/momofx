<?php
if(!defined('EMLOG_ROOT')) {exit('error!');}
function callback_init(){
	$plugin_storage = Storage::getInstance('Ixc_safe');
    $page = $plugin_storage->getValue('page');
    if(empty($r)){
		$plugin_storage->setValue('page', 'style_1');
    }
}

function callback_rm() {
    $plugin_storage = Storage::getInstance('Ixc_safe');
    $ak = $plugin_storage->deleteAllName('YES');
}