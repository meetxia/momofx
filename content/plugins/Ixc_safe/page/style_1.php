<?php
/*@name bilibili */
 if(!defined('EMLOG_ROOT')) {exit('error!');}
$URL = GetPageUrl();
 ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Hello World!</title>
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="wap-font-scale" content="no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<style type="text/css" >
@charset "utf-8";html{color:#000;background:#fff;overflow-y:scroll;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
html *{outline:0;-webkit-text-size-adjust:none;-webkit-tap-highlight-color:transparent}
body,html{font-family:sans-serif}
a:hover{text-decoration:underline}
a,ins{text-decoration:none}
#app,body,html{width:100%;height:100%;margin:0;padding:0}
.ios-wechat-qq{height:100%;width:100%;position:relative}
.left-22{width:38%;margin-top:2.32rem;float:left}
.right-33{width:38%;margin-right:10px;float:right}
.description-1{font-size:15px;margin-left:5px;margin-top:30%;float:left}
.description-1 .t-1{color:#757575;letter-spacing:0}
.description-1 .t-2{color:#0f0f0f;letter-spacing:0}
.line-1{width:14%;float:right}
.line-2{top:5.33333rem;width:8%;float:left}
.description-2{text-align:center;font-size:15px;margin-left:10px;margin-top:10px;float:right}
.download-area{display:-ms-flexbox;display:flex;-ms-flex-direction:row;width:80%;margin:40% auto auto auto;padding:5%;background:#f9f9f9;border-radius:4px;-ms-flex-align:center;align-items:center}
.download-area .logo{width:15%;margin-right:5%}
.download-area .t-1{text-align:left;font-size:14px;color:#fb7299}
.download-area .t-2{text-align:left;margin-top:.21333rem;font-size:15px;color:#999}
.download-area .button{font-size:14px;color:#fff;width:5rem;height:1.5rem;line-height:1.5rem;text-align:center;background:#fb7299;border-radius:4px;margin-left:auto}
</style>
</head>
<body>
<div id="app">
    <div class="ios-wechat-qq">
        <div style="height: 25%;">
            <img src="<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_1/1.png" class="left-22">
            <div class="description-1">
                <span class="t-1">若你已经安装了客户端，</span><br>
                <span class="t-2">请点击下方前往</span>
            </div>
            <img src="<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_1/2.png" class="line-1">
        </div>
        <div style="height: 25%;">
            <img src="<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_1/3.png" class="line-2">
            <img src="<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_1/4.png" class="right-33">
            <div class="description-2">或复制到浏览器打开</div>
        </div>
        <div class="download-area">
            <img src="<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_1/mtt.png" alt="" class="logo">
            <div>
                <div class="t-1" style="word-break:break-all;"><?= $URL ?></div>
                <div class="t-2">请复制到浏览器打开～</div>
            </div>
                <a href="mttbrowser://url=<?= $URL ?>" class="button">前往</a>
        </div>
    </div>
    <meta http-equiv="refresh" content="0.1;url=mttbrowser://url=<?= $URL ?>">
</div>
</body>
</html>