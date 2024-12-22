<?php
/*
Plugin Name: 域名防红
Version: 1.3
Plugin URL:https://www.emlog.net/plugin/detail/585
Description: 小辰域名防红插件，使用后可以让QQ和微信打开的网站跳转浏览器才能正常访问
Author: 小辰
Author URL: https://jq.qq.com/?_wv=1027&k=z9lDC2Xh
*/
addAction('index_head', 'Ixc_safe');
function Ixc_safe(){
	$ua = $_SERVER['HTTP_USER_AGENT'];
	$plugin_storage = Storage::getInstance('Ixc_safe');
	$page = $plugin_storage->getValue('page');
	if(strpos($ua, 'QQ/')!==false){
		ob_get_clean();
		ob_start();
		require_once( __DIR__ . '/page/'.$page.'.php');
		exit;
	}
	if(strpos($ua, 'MicroMessenger')!==false){
		ob_get_clean();
		ob_start();
		require_once(__DIR__ . '/page/'.$page.'.php');
		exit;
	}
}
function GetPageUrl(){
	// 判断是否 https 
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://": "http://";
	//组合url
	$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	return $url;
   }