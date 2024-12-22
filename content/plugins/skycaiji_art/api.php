<?php
require_once('../../../init.php');//引入emlog文件
!defined('EMLOG_ROOT') && exit('access deined!');

require_once 'source/skycaijiart.php';
$scjart=new skycaijiart();

$data=$scjart->caiji($_GET['url']);
$content=$data['data']['content'];
if($content){
    $emlogIsPro=stripos(Option::EMLOG_VERSION, 'pro')===0?true:false;
    if($emlogIsPro){
        //html转Markdown，仅在emlog pro中有效
        require 'vendor/autoload.php';
        $converter=new \League\HTMLToMarkdown\HtmlConverter();
        $content=preg_replace('/<div[^<>]*>/i','<p>',$content);
        $content=preg_replace('/<\/div[^<>]*>/i','</p>',$content);
        $content=preg_replace('/<(style|script)[^<>]*>[\s\S]*?<\/\1>/i', '', $content);//去除脚本
        $content=strip_tags($content,'<p><strong><em><a><blockquote><pre><code><img><ol><ul><h1><h2><h3><h4><h5><h6><hr>');
        
        $content=$converter->convert($content);
        
        $data['data']['content']=$content;
    }
}
$scjart->json($data);
