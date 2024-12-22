<?php
!defined('EMLOG_ROOT') && exit('access deined!');

//准备工作
$plugin_name = trim(basename(__DIR__), '/');

//定义系统配置
$em_option = Option::getAll();

//监测是否激活此插件
if(strpos($em_option['active_plugins'], $plugin_name) === false){
	emMsg('插件未激活，请激活');
}

/**
 * 插件激活后执行
 */
function plugin_setting_view() {
    toEverDownPage::getInstance()->admin_iframe();
}

/**
 * 路由分配
 */
$route = Input::requestStrVar('route');
if(!empty($route)){
	//检测方法是否存在
	if(!method_exists('toEverDownPage', $route)){
		emMsg('控制器不存在');
	}
	toEverDownPage::getInstance()->$route();
}