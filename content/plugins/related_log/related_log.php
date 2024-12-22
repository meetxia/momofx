<?php
/*
Plugin Name: 相关文章推荐
Version: 0.2
Plugin URL:
Description: 在文章详情页面展示与该文章相关的其他文章，推荐给读者阅读
Author: emlog
Author URL: https://www.emlog.net/
*/

!defined('EMLOG_ROOT') && exit('access deined!');

addAction('adm_sidebar_ext', 'related_log_menu');

function related_log($logData = array()) {

    $plugin_storage = Storage::getInstance('related_log');
    $data = $plugin_storage->getValue('data');

    $related_log_type = isset($data['related_log_type']) ? $data['related_log_type'] : 'sort';
    $related_log_sort = isset($data['related_log_sort']) ? $data['related_log_sort'] : 'views_desc';
    $related_log_num = isset($data['related_log_num']) ? $data['related_log_num'] : 10;
    $related_inrss = isset($data['related_inrss']) ? $data['related_inrss'] : 'n';

    global $value;
    $DB = MySql::getInstance();
    $CACHE = Cache::getInstance();
    extract($logData);
    if ($value) {
        $logid = $value['id'];
        $sortid = $value['sortid'];
        global $abstract;
    }
    $sql = "SELECT gid,title FROM " . DB_PREFIX . "blog WHERE hide='n' AND type='blog'";
    if ($related_log_type == 'tag') {
        $log_cache_tags = $CACHE->readCache('logtags');
        $Tag_Model = new Tag_Model();
        $related_log_id_str = '0';
        foreach ($log_cache_tags[$logid] as $key => $val) {
            $related_log_id_str .= ',' . $Tag_Model->getTagByName($val['tagname']);
        }
        $sql .= " AND gid!=$logid AND gid IN ($related_log_id_str)";
    } else {
        $sql .= " AND gid!=$logid AND sortid=$sortid";
    }
    switch ($related_log_sort) {
        case 'views_desc':
        {
            $sql .= " ORDER BY views DESC";
            break;
        }
        case 'views_asc':
        {
            $sql .= " ORDER BY views ASC";
            break;
        }
        case 'comnum_desc':
        {
            $sql .= " ORDER BY comnum DESC";
            break;
        }
        case 'comnum_asc':
        {
            $sql .= " ORDER BY comnum ASC";
            break;
        }
        case 'rand':
        {
            $sql .= " ORDER BY rand()";
            break;
        }
    }
    $sql .= " LIMIT 0,$related_log_num";
    $related_logs = array();
    $query = $DB->query($sql);
    while ($row = $DB->fetch_array($query)) {
        $row['gid'] = intval($row['gid']);
        $row['title'] = htmlspecialchars($row['title']);
        $related_logs[] = $row;
    }
    $out = '';
    if (!empty($related_logs)) {
        $out .= "<div style=\"font-size:16px; margin:10px 15px;\"><p><b>推荐阅读：</b></p>";
        $out .= "<p id=\"related_log\" style=\"font-size:14px;line-height: 2.0; margin:10px 10px;\">";
        foreach ($related_logs as $val) {
            $out .= "<a href=\"" . Url::log($val['gid']) . "\">{$val['title']}</a><br>";
        }
        $out .= "</p></div>";
    }
    if (!empty($value['content'])) {
        if ($related_inrss == 'y') {
            $abstract .= $out;
        }
    } else {
        echo $out;
    }
}

addAction('log_related', 'related_log');
addAction('rss_display', 'related_log');
