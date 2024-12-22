<?php
/*
 * Template Name:二次元博客主题
Description:二次元博客模板。
Author:酷乐网
Version:2.7.5
*/
if(!defined('EMLOG_ROOT')) {
    exit('error!');
}
require_once View::getView('module');
if(_g('runtime')):
runStartTime();
endif;
header("X-XSS-Protection: 1; mode=block;X-Content-Type-Options:nosniff");

;?>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php if(_g('favicon_src')){?><?php  favicon();?><?php } ?>
    <title><?php echo $site_title	;?></title>
    <?php if(isset($logid)):?>
    <meta name="keywords" content="<?php page_tag_key($logid);?>" />
    <?php else: ?>
    <meta name="keywords" content="<?php echo $site_key; ?>" />
    <?php endif;
    ?>
    <meta name="description" content="<?php echo $site_description;?>" />
    <link id="theme-style" href="<?php echo coolmeow_pic_src()?>/css/themes.css?<?php echo Version()?>" rel="stylesheet" />
    <link id="theme-style" href="<?php echo coolmeow_pic_src()?>/css/style.css?<?php echo Version()?>" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo coolmeow_pic_src()?>/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo coolmeow_pic_src()?>/js/jquery.pjax.min.js"></script>
    <?php if (_g('body_grey')){?>
        <style type="text/css">html{ filter: grayscale(100%); -webkit-filter: grayscale(100%); -moz-filter: grayscale(100%); -ms-filter: grayscale(100%); -o-filter: grayscale(100%); filter: url("data:image/svg+xml;utf8,#grayscale"); filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1); -webkit-filter: grayscale(1);} </style><?php } ?>
    <meta name='robots' content='max-image-preview:large' />
    <style id="erphpdown-custom"></style>
    <?php if (_g('banner_height')!='') :?>
    <style>.section-blog-cover{height:<?php echo _g('banner_height');?>px;}</style>
    <?php endif;?>
    <?php if (_g('m_banner_height')!='') :?>
        <style>@media (max-width:767px){.section-blog-cover {height:<?php echo _g('m_banner_height');?>px;}}
        </style>
    <?php endif;?>
    <script>function sendinfo(url) {
            $("#calendar").load(url)
        }</script>
    <?php doAction('index_head') ?>
</head>
<body>
<body class="index-page <?php if(_g('body_color')) echo 'body_color';?>">
<?php load_lantern()?>
<div id="preloader">
    <div class="book">
        <div class="inner">
            <div class="left"></div>
            <div class="middle"></div>
            <div class="right"></div>
        </div>
        <ul>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
</div>
<div id="boxmoe_global">
    <header class="header-global">
        <nav id="navbar-main" class="navbar navbar-expand-lg navbar-light navbar-transparent headroom headroom--top headroom--not-bottom">
            <div class="container">
                <a class="logo navbar-brand font-weight-bold" href="<?php echo BLOG_URL;?>" title="<?php echo $blogname;?>">
                    <img src="<?php echo _g('logo');?>" alt="<?php echo $blogname;?>" class="headerlogo"></a>
                <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon mt-2">
              <span class="navbar-toggler-bar bar1"></span>
              <span class="navbar-toggler-bar bar2"></span>
              <span class="navbar-toggler-bar bar3"></span>
            </span>
                </button>
                <div class="collapse navbar-collapse w-100 pt-3 pb-2 py-lg-0" id="navigation">
                    <?php
                    global $CACHE;
                    if($logid){
                        $log_cache_sort = $CACHE->readCache('logsort');
                        $cache_sort = $CACHE->readCache('sort');

                        $pid = $cache_sort[$sortid]['pid'];
                        $sid=$cache_sort[$sortid]['sid'];
                        $sorturl=Url::sort($pid);                blog_navi($sorturl);
                    }elseif($sort){
                        global $CACHE;
                        $cache_sort = $CACHE->readCache('sort');
                        $pid = $cache_sort[$sortid]['pid'];
                        $sid=$cache_sort[$sortid]['sid'];
                        if ($pid != 0) {
                            $sorturl=Url::sort($pid);
                        }else{
                            $sorturl=Url::sort($sid);
                        }
                        blog_navi($sorturl);
                    }else{
                        $sorturl = BLOG_URL . trim(Dispatcher::setPath(), '/');
                        blog_navi($sorturl);
                    }?>
                </div>
            </div>
        </nav>
    </header>

    <section class="section-blog-cover section-shaped my-0" <?php echo banner();?>>
        <div class="separator separator-bottom separator-skew">
            <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
                <defs>
                    <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
                </defs>
                <g class="parallax">
                    <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7"></use>
                    <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)"></use>
                    <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)"></use>
                    <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff"></use>
                </g>
            </svg>
        </div>
    </section>

