<?php
/*
Plugin Name: 文章一键采集
Version: 1.0
Plugin URL: https://www.skycaiji.com
Description: 写文章时输入任意网址即可将标题和正文采集到文章中
Author: 蓝天采集
Author URL: https://www.skycaiji.com
*/
!defined('EMLOG_ROOT') && exit('access deined!');

function skycaiji_art_html(){
    require_once 'source/skycaijiart.php';
    $scjart=new skycaijiart();
    $emlogIsPro=stripos(Option::EMLOG_VERSION, 'pro')===0?1:'';
$html=<<<EOF
<a href="javascript:displayToggle('skycaiji_art_box', 0);">[文章一键采集]</a>
<div class="form-inline" id="skycaiji_art_box" style="display:none;padding:10px 0;">
    <input type="text" id="skycaiji_art_url" class="form-control" value="" placeholder="输入网址" />
    &nbsp; <button type="button" class="btn btn-primary" id="skycaiji_art_btn">一键采集</button>
</div>
<script type="text/javascript">
function skycaiji_art_func(title,content){
    var isPro='{$emlogIsPro}';
    document.getElementById('title').value=title;
    if(isPro){
        Editor.setValue(content);
    }else{
        var ifrs=document.getElementsByClassName('ke-edit-iframe');
        ifrs[0].contentWindow.document.body.innerHTML=content;
    }
}
</script>
EOF;
    
    $html=$scjart->initCaijiHtml($html, BLOG_URL.'content/plugins/skycaiji_art/api.php?url=');

    echo $html;
}

addAction('adm_writelog_head','skycaiji_art_html');