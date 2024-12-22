<?php
!defined('EMLOG_ROOT') && exit('access deined!');

function callback_init() {
	$plugin_storage = Storage::getInstance('content_lv_cv');
	$plugin_storage->setValue('content_img', 'https://pic.imgdb.cn/item/63e77add4757feff33c6f753.png');
	$plugin_storage->setValue('content_dis', 'inline-block');
	$plugin_storage->setValue('content_urlo', '#comment-post');
	$plugin_storage->setValue('content_urlt', '/admin');
	$plugin_storage->setValue('content_css', 'border:1px dashed#ff9a9a;background:#fffff;padding:5px 0 5px 25px;margin:5px 10px;color:#FF0000;line-height:26px;text-align:center;');
	$plugin_storage->setValue('c_wxtoken', 'emlog');
	$plugin_storage->setValue('c_wxqr', 'https://www.luyuz.cn/content/templates/luyu_fee/static/img/wx-mp.jpg');
	$plugin_storage->setValue('c_tips', 'emlog');
	$plugin_storage->setValue('c_maxage','3');
}

function callback_rm() {
    $plugin_storage = Storage::getInstance('content_lv_cv'); 
    $ak = $plugin_storage->deleteAllName('YES'); 
}


function callback_up() {
    
}

?>