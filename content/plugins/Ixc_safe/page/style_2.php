<?php
/*@name 沁影样式 */
 if(!defined('EMLOG_ROOT')) {exit('error!');}
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--禁止全屏缩放-->
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <!--不显示成手机号-->
    <meta name="format-detection" content="telephone=no" />
    <!--删除默认的苹果工具栏和菜单栏-->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!--解决UC手机字体变大的问题-->
    <meta name="wap-font-scale" content="no" />
    <!--控制状态栏显示样式-->
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <!--引入css-->
    <link href="<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_2/css/fanghong.css" rel="stylesheet" type="text/css" />
    <title>Hello World!</title>
</head>
<body style="background-color:#f4f4f4">
    <img style="max-width:100%;overflow:hidden;" src="<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_2/img/jiantou.png" alt="箭头图">
    <h1 align="center">请在浏览器打开</h1>
    <div align="center">本页网址<p id="url"></p><button onclick="copyurl">点此复制</button></div>
    <img style="max-width:100%;overflow:hidden;" src="<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_2/img/caidan.png" alt="菜单图">
    <p id="url" ></p>
<script>
let url = location.href;
document.getElementById("url").innerHTML = url;
</script>
</body>
</html>