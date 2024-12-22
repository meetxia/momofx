<?php
/********
 * 版权声明：
 * 版权所有 © [天津点滴记忆信息科技有限公司]
 * 本软件受到版权法和国际版权条约的保护。未经许可，任何人不得复制、修改、发布、出售、分发本软件的任何部分。
 * 未经许可，不得将本软件用于任何商业用途。任何人不得使用本软件进行任何违法活动。
 * 未经授权使用本软件的任何行为都将受到法律追究。
 * [天津点滴记忆信息科技有限公司] 保留所有权利。
 ********/

!defined('EMLOG_ROOT') && exit('access denied!');

if (!class_exists('EmStats', false)) {
    include __DIR__ . '/em_stats.php';
}

// 开启插件时执行该函数
function callback_init() {
    $emStats = EmStats::getInstance();
    $table = $emStats->getTable('data');
    $charset = 'utf8mb4';
    $type = 'InnoDB';
    $add = "ENGINE=$type DEFAULT CHARSET=$charset;";
    $sql = "
	CREATE TABLE IF NOT EXISTS `$table` (
		`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`gid` int(11) unsigned NOT NULL,
		`title` varchar(255) NOT NULL default '',
		`views` bigint(11) unsigned NOT NULL default 0,
		`comments` bigint(11) unsigned NOT NULL default 0,
		`date` date NOT NULL,
		PRIMARY KEY (`id`),
		UNIQUE KEY `date_gid` (`date`,`gid`)
	)" . $add;
    $emStats->getDb()->query($sql);
}

// 关闭和删除插件时执行该函数
function callback_rm() {
    // do something
}
