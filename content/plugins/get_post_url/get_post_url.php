<?php

/*
 * Plugin Name: 文章URL列表获取
 * Version: 0.4
 * Plugin URL: https://utf-x.cn/
 * Description: 集中列出所有文章的URL，方便提交收录。
 * ForEmlog: Pro
 * Author: UTF-X
 * Author URL: https://utf-x.cn/
 */

!defined('EMLOG_ROOT') && exit('access deined!');

function gpu_ame()
{
    echo '<a class="collapse-item" id="gpu_ame" href="plugin.php?plugin=get_post_url">文章URL列表</a>';
}

addAction('adm_menu_ext', 'gpu_ame');
