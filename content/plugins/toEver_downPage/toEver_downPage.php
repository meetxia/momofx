<?php
/*
Plugin Name: 小风下载插件
Version: 1.3.0
Description: 双模式下载插件，支持单页模式及非单页模式，toEver主题已完整支持。
Author: 小风
Author URL: http://woaif.cn
*/
!defined('EMLOG_ROOT') && exit('access deined!');

//定义常量
define('TOEVER_DOWNPAGE_VERSION', '1.3.0');
define('TOEVER_DOWNPAGE_ROOT', __DIR__);
define('TOEVER_DOWNPAGE_NAME', trim(basename(TOEVER_DOWNPAGE_ROOT), '/'));
define('TOEVER_DOWNPAGE_URL', BLOG_URL . 'content/plugins/' . TOEVER_DOWNPAGE_NAME . '/');
//加载核心库
require_once TOEVER_DOWNPAGE_ROOT . '/core/index.php';
//加载支付核心库
require_once TOEVER_DOWNPAGE_ROOT . '/core/pay.php';

//css前台配置函数
addAction('index_head', function(){
	echo '<script type="text/javascript">
		window.toEverDownPage = {
			plugin_name: "'.TOEVER_DOWNPAGE_NAME.'",
			plugin_path: "'.TOEVER_DOWNPAGE_URL.'",
			plugin_ver: "'.TOEVER_DOWNPAGE_VERSION.'"
		};
	</script>
	<link type="text/css" rel="stylesheet" href="'.TOEVER_DOWNPAGE_URL.'static/index/main.css?v='.TOEVER_DOWNPAGE_VERSION.'">
	<script type="text/javascript" src="'.TOEVER_DOWNPAGE_URL.'static/index/main.js?v='.TOEVER_DOWNPAGE_VERSION.'" id="'.TOEVER_DOWNPAGE_NAME.'-index-js"></script>
	';
});

//css后台配置函数
addAction('adm_head', function(){
	echo '<link type="text/css" rel="stylesheet" href="'.TOEVER_DOWNPAGE_URL.'static/admin/main.css?t='.TOEVER_DOWNPAGE_VERSION.'">';
});

//js后台配置函数
addAction('adm_footer', function(){
	echo '<script type="text/javascript" src="'.TOEVER_DOWNPAGE_URL.'static/admin/main.js?t='.TOEVER_DOWNPAGE_VERSION.'"></script>';
});

//后台菜单添加
addAction('adm_menu', function(){
    toEverDownPage::getInstance()->admin_menu();
});

//后台表单添加
addAction('adm_writelog_side', function(){
    toEverDownPage::getInstance()->admin_form();
});

//后台表单提交
addAction('save_log', function($logid){
    toEverDownPage::getInstance()->admin_form_save($logid, $action);
});

//前台输出
addAction('log_related', function($logData){
    toEverDownPage::getInstance()->index_view($logData);
});

//评论回复cookie存储
addAction('comment_saved', function(){
    setcookie("toEverDownPage_comment_gid", $_POST['gid'], time() + 31536000);
});