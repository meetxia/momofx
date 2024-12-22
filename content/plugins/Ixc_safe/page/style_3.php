<?php
/*@name 苹果样式 */
 if(!defined('EMLOG_ROOT')) {exit('error!');}
 $URL = GetPageUrl();
 ?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hello World!</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta name="format-detection" content="telephone=no">
    <meta content="false" name="twcClient" id="twcClient">
    <meta name="aplus-touch" content="1">
    <style>
        body,html{width:100%;height:100%}
        *{margin:0;padding:0}
        body{background-color:#fff}
        #browser img{
            width:50px;
        }
        #browser{
            margin: 0px 10px;
            text-align:center;
        }
        #contens{
            font-weight: bold;
            color: #2466f4;
            margin:-285px 0px 10px;
            text-align:center;
            font-size:20px;
            margin-bottom: 125px;
        }
        .top-bar-guidance{font-size:15px;color:#fff;height:70%;line-height:1.8;padding-left:20px;padding-top:20px;background:url(<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_3/banner.png) center top/contain no-repeat}
        .top-bar-guidance .icon-safari{width:25px;height:25px;vertical-align:middle;margin:0 .2em}
        .app-download-tip{margin:0 auto;width:290px;text-align:center;font-size:15px;color:#2466f4;background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAcAQMAAACak0ePAAAABlBMVEUAAAAdYfh+GakkAAAAAXRSTlMAQObYZgAAAA5JREFUCNdjwA8acEkAAAy4AIE4hQq/AAAAAElFTkSuQmCC) left center/auto 15px repeat-x}
        .app-download-tip .guidance-desc{background-color:#fff;padding:0 5px}
        .app-download-tip .icon-sgd{width:25px;height:25px;vertical-align:middle;margin:0 .2em}
        .app-download-btn{display:block;width:214px;height:40px;line-height:40px;margin:18px auto 0 auto;text-align:center;font-size:18px;color:#2466f4;border-radius:20px;border:.5px #2466f4 solid;text-decoration:none}
    </style>
</head>
<body>

<div class="top-bar-guidance">
    <p>点击右上角<img src="<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_3/3dian.png" class="icon-safari">在 浏览器 打开</p>
    <p>苹果设备<img src="<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_3/iphone.png" class="icon-safari">安卓设备<img src="<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_3/android.png" class="icon-safari">↗↗↗</p>
</div>

<div id="contens">
<p><br/><br/></p>
<p>1.本站不支持 微信或QQ 内访问</p>
<p><br/></p>
<p>2.请按提示在手机 浏览器 打开</p>
</div>
<div class="app-download-tip">
    <span class="guidance-desc"><?= $URL ?></span>
</div>
<p><br/></p>
<div class="app-download-tip">
    <span class="guidance-desc">点击右上角<img src="<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_3/3dian.png" class="icon-sgd"> or 复制网址自行打开</span>
</div>

<script type="text/javascript">$.getScript("https://baidu.com/",function(data){});</script>
<script src="<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_3/jquery-3.3.1.min.js"></script>
<script src="<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_3/clipboard.min.js"></script>
<a data-clipboard-text="<?= $URL ?>" class="app-download-btn"  >点此复制本站网址</a>
<script src="https://cdn.staticfile.org/jquery/1.12.3/jquery.min.js"></script>
<script src="<?=BLOG_URL;?>content/plugins/Ixc_safe/page/style_3/layer/layer.js"></script>
<script type="text/javascript">new ClipboardJS(".app-download-btn");</script>
<script>
$(".app-download-btn").click(function() {
layer.msg("复制成功，浏览器打开", function(){
      //关闭后的操作
      });})
</script>
<body>
</html>