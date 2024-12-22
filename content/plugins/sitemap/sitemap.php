<?php
/*
Plugin Name: 站点地图-sitemap
Version: 2.1.4
Plugin URL: https://www.emlog.net/plugin/detail/11
Description: 生成sitemap，供搜索引擎抓取
Author: emlog
Author URL: https://www.emlog.net/plugin/index/author/577
*/

!defined('EMLOG_ROOT') && exit('access denied!');

function sitemap_update() {
    require_once EMLOG_ROOT . '/content/plugins/sitemap/class.sitemap.php';
    extract(sitemap_config());
    $sitemap = new sitemap($sitemap_name);
    return $sitemap->build();
}

function sitemap_del($logid) {
    global $sitemap_name;
    $url = Url::log($logid);
    $file = EMLOG_ROOT . '/' . $sitemap_name;
    $xml = file_get_contents($file);
    $xml = preg_replace("|<url>\n<loc>" . preg_quote($url) . "<\/loc>.*?<\/url>\n|is", "", $xml);
    file_put_contents($file, $xml);
}

function sitemap_update_on_comment() {
    global $sitemap_name;
    if (Option::get('ischkcomment') == 'n') {
        return;
    }
    $gid = isset($_POST['gid']) ? intval($_POST['gid']) : -1;
    $url = Url::log($gid);
    $lastmod = date('c');
    $file = EMLOG_ROOT . '/' . $sitemap_name;
    $xml = file_get_contents($file);
    $xml = preg_replace("|<loc>" . preg_quote($url) . "<\/loc>\n<lastmod>(.*?)<\/lastmod>|i", "<loc>$url</loc>\n<lastmod>$lastmod</lastmod>", $xml);
    file_put_contents($file, $xml);
}

function sitemap_footer() {
    global $sitemap_name;
    echo '<a href="' . BLOG_URL . $sitemap_name . '" rel="sitemap">sitemap</a>';
}

function sitemap_config() {
    $sitemap = array(
        'sitemap_name'         => 'sitemap.xml',
        'sitemap_changefreq'   => array(
            'weekly',
            'daily',
            'daily',
            'daily',
            'daily'
        ),
        'sitemap_priority'     => array(
            '0.8',
            '0.8',
            '0.8',
            '0.8',
            '0.9'
        ),
        'sitemap_show_footer'  => '1',
        'sitemap_comment_time' => '1'
    );
    $plugin_storage = Storage::getInstance('sitemap');
    $conf_data = $plugin_storage->getValue('conf_data');
    if (empty($conf_data)) {
        return $sitemap;
    }
    return $conf_data;
}

extract(sitemap_config());
addAction('save_log', 'sitemap_update');
addAction('del_log', 'sitemap_del');
if ($sitemap_comment_time) {
    addAction('comment_saved', 'sitemap_update_on_comment');
}
if ($sitemap_show_footer) {
    addAction('index_footer', 'sitemap_footer');
}
if (Option::get('istwitter') == 'y') {
    addAction('post_twitter', 'sitemap_update');
}

