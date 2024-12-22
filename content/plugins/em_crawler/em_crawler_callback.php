<?php
!defined('EMLOG_ROOT') && exit('access denied!');

if (!class_exists('EmCrawler', false)) {
    include __DIR__ . '/em_crawler.php';
}

// 开启插件时执行该函数
function callback_init() {
    $emCrawler = EmCrawler::getInstance();
    $table = $emCrawler->getTable('data');
    $charset = 'utf8mb4';
    $type = 'InnoDB';
    $add = "ENGINE=$type DEFAULT CHARSET=$charset;";
    $sql = "
	CREATE TABLE IF NOT EXISTS `$table` (
		`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`name` varchar(255) NOT NULL default '',
		`ua` varchar(255) NOT NULL default '',
		`url` varchar(255) NOT NULL default '',
		`date` datetime NOT NULL,
		PRIMARY KEY (`id`)
	)" . $add;
    $emCrawler->getDb()->query($sql);
}

// 关闭和删除插件时执行该函数
function callback_rm() {
    $emCrawler = EmCrawler::getInstance();
    $table = $emCrawler->getTable('data');
    $sql = "DROP TABLE IF EXISTS `$table`;";
    $emCrawler->getDb()->query($sql);
}
