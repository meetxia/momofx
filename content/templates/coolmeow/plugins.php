<?php
/*
Plugin Name: 酷乐二次元主题配套插件
Version: 1.1
Plugin URL:https://kule66.com
Description: 酷乐二次元主题配套插件
Author: 酷酷的酷乐
Author URL:https://kule66.com
*/
defined('EMLOG_ROOT') || exit('access denied!');
function meow_down_admin_show(){
    $db = MySql::getInstance();
    $gid = isset($_GET['gid']) ? addslashes(trim($_GET['gid'])) : '';
    if ($gid){
        $sql = "SELECT * FROM " . DB_PREFIX . "meowdown WHERE logid=" . $gid . ";";
        $result = $db->query($sql);
        $meow = $db->fetch_array($result);

    }else {
        $meow = array(
            'down1' => '',
            'down2' => '',
            'down3' => '',
        );
    }
    ?>
    <div class="card shadow mb-4 mt-2">
        <div class="card-body">
            <div class="form-group">
                <h4>meow下载设置</h4>
            </div>
            <div class="form-group">
                <table class="table">
                    <tbody>
                    <tr>
                        <th style="width:10%;"><label>夸克</label></th>
                        <th><input type="text" class="form-control" name="down1" id="down1" value="<?php echo $meow['down1']; ?>" size="30" tabindex="30" style="width: 80%;"></th>
                    </tr>
                    <tr>
                        <th style="width:10%;"><label>移动云盘</label></th>
                        <th><input type="text" class="form-control" name="down2" id="down2" value="<?php echo $meow['down2']; ?>" size="30" tabindex="30" style="width: 80%;"></th>
                    </tr>
                    <tr>
                        <th style="width:10%;"><label>123盘</label></th>
                        <th><input type="text" class="form-control" name="down3" id="down3" value="<?php echo $meow['down3']; ?>" size="30" tabindex="30" style="width: 80%;"></th>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <?php
}
function neow_Plugin_blog_save($blogid){
    $db = MySql::getInstance();
    $data = $db->query("SELECT * FROM ".DB_PREFIX."meowdown WHERE logid ='$blogid'");
    $row = $db->fetch_array($data);
    $actionType = 'unknown';
    $currentURL = $_SERVER['HTTP_REFERER'];
    if (strpos($currentURL, 'action=edit') !== false) {
        $actionType = 'edit';
    }
    elseif (strpos($currentURL, 'action=write') !== false) {
        $actionType = 'create';
    }
    $down1 = Input::postStrVar('down1');
    $down2 = Input::postStrVar('down2');
    $down3 = Input::postStrVar('down3');
    $gid = $blogid;
    $neowData = [
        'down1'=>$down1,
        'down2'=>$down2,
        'down3'=>$down3,
        'logid'=>$gid
    ];
    $neow = new neow();

    if ($actionType =='create' or !$row) {
        $neow->Addneow($neowData);
    } else {
        $neow->Updateneow($neowData,$gid);
    }
}
function neow_Plugin_blog_delete($blogid){
    $neow = new neow();
    $neow->Delneow($blogid);
}
addAction('del_log', 'neow_Plugin_blog_delete');
class neow{
    private $db;
    private $table;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->table = DB_PREFIX . 'meowdown';
    }
    public function Addneow($neowData) {
        $kItem = $dItem = [];
        foreach ($neowData as $key => $data) {
            $kItem[] = $key;
            $dItem[] = $data;
        }
        $field = implode(',', $kItem);
        $values = "'" . implode("','", $dItem) . "'";
        $this->db->query("INSERT INTO $this->table ($field) VALUES ($values)");
        return $this->db->insert_id();
    }
    public function Updateneow($neowData, $blogId) {
        $Item = [];
        foreach ($neowData as $key => $data) {
            $Item[] = "$key='$data'";
        }
        $upStr = implode(',', $Item);
        $this->db->query("UPDATE $this->table SET $upStr WHERE logid=$blogId");
    }
    public function Delneow($blogId) {

        $this->db->query("DELETE FROM $this->table where logid=$blogId");

    }

}
addAction('save_log', 'neow_Plugin_blog_save');
addAction('adm_writelog_head', 'meow_down_admin_show');
addAction('down_log', 'meow_down');
function contentstyle(){
    ?>
    <script type="text/javascript">
        function insert_contentstyle(str) {
            let selectIndex=str.selectedIndex;
            Editor.insertValue(str.options[selectIndex].value + '\n');
        }
    </script>
    <div class="form-group" id="contentstyle">
        <label>样式美化方式：</label>
        <select id="short_code_select" class="form-control" onChange="insert_contentstyle(this)">
            <option value="短代码选择！">Boxmoe短代码</option>
            <option value="[h2]内容[/h2]">H2标题</option>
            <option value="[h3]内容[/h3]">H3标题</option>
            <option value="[h2set]内容[/h2set]">H2设置标</option>
            <option value="[h2pen]内容[/h2pen]">H2毛笔标</option>
            <option value="[h2wechat]内容[/h2wechat]">H2微信标</option>
            <option value="[h2mht]内容[/h2mht]">H2QQ企鹅标</option>
            <option value="[h2down]内容[/h2down]">H2下载标</option>
            <option value="[downloadbtn link='链接']按钮名称[/downloadbtn]">下载按钮</option>
            <option value="[linksbtn link='链接']按钮名称[/linksbtn]">链接按钮</option>
            <option value="[flybtn]按钮名称[/flybtn]">飞来模块</option>
            <option value="[code]内容[/code]">code模块</option>
            <option value="[blockquote1 name='签名']内容[/blockquote1]">引用模块1</option>
            <option value="[blockquote2 name='签名']内容[/blockquote2]">引用模块2</option>
            <option value="[yaowan style='输入数字1-16共16个模式颜色']内容[/yaowan]">药丸模块</option>
            <option value="[alert style='输入数字1-7共7个模式颜色']内容[/alert]">警告框模块</option>
            <option value="[iframe link='链接']内容[/iframe]">Iframe</option>
            <option value="[userreading]隐藏内容[/userreading]">登录查看一</option>
            <option value="[userstyle]隐藏内容[/userstyle]">登录查看二</option>
            <option value="[pwd_protected_post]隐藏内容[/pwd_protected_post]">评论查看一</option>
            <option value="[pwd_protected_style]隐藏内容[/pwd_protected_style]">评论查看二</option>
            <option value="[audio link='音频链接'][/audio]">插入音频</option>
            <option value="[video link='视频链接'][/video]">插入视频</option>
            <option value="<div class='timeline timeline-one-side' data-timeline-content='axis' data-timeline-axis-style='dashed'>
<div class='timeline-block'>
<span class='timeline-step badge-success'>
<i class='fa fa-bell'></i>
</span>
<div class='timeline-content'>
<small class='text-muted font-weight-bold'>2021年1月1日</small
<h5 class=' mt-3 mb-0'>主题</h5>
<p class=' text-sm mt-1 mb-0'>内容段</p>
</div>
</div>
<!--时间段时间开始-->
<div class='timeline-block'>
<span class='timeline-step badge-success'>
<i class='fa fa-clock-o'></i>
</span>
<div class='timeline-content'>
<small class='text-muted font-weight-bold'>2021年1月1日</small
<h5 class=' mt-3 mb-0'>主题</h5>
<p class=' text-sm mt-1 mb-0'>内容段</p>
</div>
</div>
<!--时间段时间结束，此段可无限复制往下排列-->


<!--以上时间段区--></div>">时间线1(切换文本代码编辑)</option>
            <option value="<ul class='timelines'>
<!--时间段时间开始-->
  <li class='timeline-event'>
    <label class='timeline-event-icon'></label>
    <div class='timeline-event-copy'>
      <p class='timeline-event-thumbnail'>2020/03/05</p>
      <h3>h3标题</h3>
      <h4>H4标题2</h4>
      <p><strong>加粗小标题</strong><br>内容</p>
    </div>
  </li>
 <!--时间段时间结束，此段可无限复制往下排列-->
</ul>">时间线2(切换文本代码编辑)</option>
            </option>
        </select>
    </div>
    <script>
        // 将 contentstyle 元素移到 logcontent 元素之前
        $("#logcontent").before($("#contentstyle"));
    </script>

    <?php


}
function neow_Admin_style(){
    ?>
    <script>
        $(document).ready(function() {
            $('body').addClass('meow_style_login');
            $('h1').before('<div class="meow_style_logo"></div>');
            $('h1').remove();
            $('div.col-xl-6').removeClass('col-xl-6').addClass('col-xl-4');
        });
    </script>
    <style>
        .meow_style_login{
            position: relative;
            width: 100%;
            background: rgba(0, 0, 0, 0) -webkit-linear-gradient(left, #ff5f6d 0%, #ffb270 100%) repeat scroll 0 0;
            background: rgba(0, 0, 0, 0) linear-gradient(to right, #ff5f6d 0%, #ffb270 100%) repeat scroll 0 0;
            overflow: hidden;
            background-size: cover;
            background-position: center center;
            z-index: 1;
            background-image: linear-gradient(to top, #a8edea 0%, #fed6e3 100%);

        }
        form.user .form-control-user,form.user .btn-user{
            border-radius: 0.35rem;
            font-size: 1rem;
        }
        .meow_style_logo{
            height: 80px;
            background-image: url(<?php echo _g('logo');?>);
            width: 180px;
            max-height: 100px;
            margin: 20px auto 15px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center center;
        }
        a{
            color: #3c434a;
        }
        .btn{
            background-color: #2271b1;
            border-color: #2271b1;
        }
        .btn:hover{
            background-color: #135e96;
            border-color: #135e96;
        }
    </style>
<?php
}
function page_link_go($link){
    $my_urls = array(
        array('blog',BLOG_URL),
    );
    if(strlen($_SERVER['REQUEST_URI']) > 384 || strpos($_SERVER['REQUEST_URI'], "eval(") || strpos($_SERVER['REQUEST_URI'], "base64")) {
        @header("HTTP/1.1 414 Request-URI Too Long");
        @header("Status: 414 Request-URI Too Long");
        @header("Connection: Close");
        @exit;
    }
    $go_url=htmlspecialchars(preg_replace('/^url=(.*)$/i','$1',$link));
//自定义URL
    foreach($my_urls as $x=>$x_value)
    {
        if($go_url==$x_value[0]) {
            echo $go_url = $x_value[1];
        }
    }
    if(!empty($go_url)) {
        if ($go_url == base64_encode(base64_decode($go_url))) {
            $go_url = base64_decode($go_url);
        }
        preg_match('/^(http|https|thunder|qqdl|ed2k|Flashget|qbrowser):\/\//i',$go_url,$matches);
        if($matches){
            $url=$go_url;
            $title= '页面加载中,请稍候...';
        } else {
            preg_match('/\./i',$go_url,$matche);
            if($matche){
                $url='https://'.$go_url;
                $title= '页面加载中,请稍候...';
            } else {
                $url = 'https://'.$_SERVER['HTTP_HOST'];
                $title='参数错误，中止跳转！正在返回首页...';
                echo "<script>setTimeout(function(){window.opener=null;window.close();}, 3000);</script>";
            }
        }
    } else {
        $title ='参数缺失，中止跳转！正在返回首页...';
        $url = 'https://'.$_SERVER['HTTP_HOST'];
        echo "<script>setTimeout(function(){window.opener=null;window.close();}, 3000);</script>";
    }

    ?>

    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="refresh" content="1;url='<?php echo $url;?>';">
        <title>
            <?php echo $title;?>
        </title>
        <style type="text/css">
            body{background:#999;margin:0;}.loader{-webkit-animation:fadein 2s;-moz-animation:fadein 2s;-o-animation:fadein 2s;animation:fadein 2s;position:absolute;top:0;left:0;right:0;bottom:0;background-color:#fff;}@-moz-keyframes fadein{from{opacity:0}to{opacity:1}}@-webkit-keyframes fadein{from{opacity:0}to{opacity:1}}@-o-keyframes fadein{from{opacity:0}to{opacity:1}}@keyframes fadein{from{opacity:0}to{opacity:1}}.loader-inner{position:absolute;z-index:300;top:40%;left:50%;-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);transform:translate(-50%,-50%);}@-webkit-keyframes rotate_pacman_half_up{0%{-webkit-transform:rotate(270deg);transform:rotate(270deg);}50%{-webkit-transform:rotate(360deg);transform:rotate(360deg);}100%{-webkit-transform:rotate(270deg);transform:rotate(270deg);}}@keyframes rotate_pacman_half_up{0%{-webkit-transform:rotate(270deg);transform:rotate(270deg);}50%{-webkit-transform:rotate(360deg);transform:rotate(360deg);}100%{-webkit-transform:rotate(270deg);transform:rotate(270deg);}}@-webkit-keyframes rotate_pacman_half_down{0%{-webkit-transform:rotate(90deg);transform:rotate(90deg);}50%{-webkit-transform:rotate(0deg);transform:rotate(0deg);}100%{-webkit-transform:rotate(90deg);transform:rotate(90deg);}}@keyframes rotate_pacman_half_down{0%{-webkit-transform:rotate(90deg);transform:rotate(90deg);}50%{-webkit-transform:rotate(0deg);transform:rotate(0deg);}100%{-webkit-transform:rotate(90deg);transform:rotate(90deg);}}@-webkit-keyframes pacman-balls{75%{opacity:0.7;}100%{-webkit-transform:translate(-100px,-6.25px);transform:translate(-100px,-6.25px);}}@keyframes pacman-balls{75%{opacity:0.7;}100%{-webkit-transform:translate(-100px,-6.25px);transform:translate(-100px,-6.25px);}}.pacman > div:nth-child(2){-webkit-animation:pacman-balls 1s 0s infinite linear;animation:pacman-balls 1s 0s infinite linear;}.pacman > div:nth-child(3){-webkit-animation:pacman-balls 1s 0.33s infinite linear;animation:pacman-balls 1s 0.33s infinite linear;}.pacman > div:nth-child(4){-webkit-animation:pacman-balls 1s 0.66s infinite linear;animation:pacman-balls 1s 0.66s infinite linear;}.pacman > div:nth-child(5){-webkit-animation:pacman-balls 1s 0.99s infinite linear;animation:pacman-balls 1s 0.99s infinite linear;}.pacman > div:first-of-type{width:0px;height:0px;border-right:25px solid transparent;border-top:25px solid #e1244e;border-left:25px solid #e1244e;border-bottom:25px solid #e1244e;border-radius:25px;-webkit-animation:rotate_pacman_half_up 0.5s 0s infinite;animation:rotate_pacman_half_up 0.5s 0s infinite;}.pacman > div:nth-child(2){width:0px;height:0px;border-right:25px solid transparent;border-top:25px solid #e1244e;border-left:25px solid #e1244e;border-bottom:25px solid #e1244e;border-radius:25px;-webkit-animation:rotate_pacman_half_down 0.5s 0s infinite;animation:rotate_pacman_half_down 0.5s 0s infinite;margin-top:-50px;}.pacman > div:nth-child(3),.pacman > div:nth-child(4),.pacman > div:nth-child(5),.pacman > div:nth-child(6){background-color:#e1244e;width:15px;height:15px;border-radius:100%;margin:2px;width:10px;height:10px;position:absolute;-webkit-transform:translate(0,-6.25px);-ms-transform:translate(0,-6.25px);transform:translate(0,-6.25px);top:25px;left:100px;}.loader-text{margin:20px 0 0 -16px;display:block;font-size: 18px;}
        </style>
    </head>

    <body>
    <div class="loader">
        <div class="loader-inner pacman">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <span class="loader-text">页面加载中，请稍候...</span>
        </div>
    </div>
    </body>

    </html>
    <?php
}
function page_link_go_to($link){
    global $CACHE;
    $options_cache = $CACHE->readCache('options');
$my_urls = array(
    array('blog',BLOG_URL),
);
$blogname=$options_cache['blogname'];
if(strlen($_SERVER['REQUEST_URI']) > 384 || strpos($_SERVER['REQUEST_URI'], "eval(") || strpos($_SERVER['REQUEST_URI'], "base64")) {
@header("HTTP/1.1 414 Request-URI Too Long");
@header("Status: 414 Request-URI Too Long");
@header("Connection: Close");
@exit;
}
$go_url=htmlspecialchars(preg_replace('/^url=(.*)$/i','$1',$link));
//自定义URL
foreach($my_urls as $x=>$x_value)
{
	if($go_url==$x_value[0]) {
		echo $go_url = $x_value[1];
		}
}
if(!empty($go_url)) {
if ($go_url == base64_encode(base64_decode($go_url))) {
$go_url = base64_decode($go_url);
}
preg_match('/^(http|https|thunder|qqdl|ed2k|Flashget|qbrowser):\/\//i',$go_url,$matches);
if($matches){
$url=$go_url;
$title= '安全中心 | 加载中...';
} else {
preg_match('/\./i',$go_url,$matche);
if($matche){
$url='https://'.$go_url;
$title= '安全中心 | 加载中...';
} else {
$err = '1';
$url = 'https://'.$_SERVER['HTTP_HOST'];
$title='参数错误，中止跳转！正在返回首页...';
}
}
} else {
$err = '1';
$title ='参数缺失，中止跳转！正在返回首页...';
$url = 'https://'.$_SERVER['HTTP_HOST'];
}
?>
<!DOCTYPE html>
<html lang="zh-CN" class="io-white-mode">
<head>
<script>
    var default_c = "io-white-mode";
    var night = document.cookie.replace(/(?:(?:^|.*;\s*)io_night_mode\s*\=\s*([^;]*).*$)|^.*$/, "$1");
    try {
        if (night === "0" || (!night && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
            document.documentElement.classList.add("io-black-mode");
            document.documentElement.classList.remove(default_c);
        } else {
            document.documentElement.classList.remove("io-black-mode");
            document.documentElement.classList.add(default_c);
        }
    } catch (_) {}
</script><meta charset="utf-8">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,height=device-height, initial-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="robots" content="noindex,follow">
<title><?php echo $blogname; ?>-<?php echo $title;?></title>
<style>
body{margin:0;padding:0}body{height:100%}#loading{-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-ms-flex-align:center;align-items:center;display:-webkit-box;display:-ms-flexbox;display:flex;position:fixed;top:0;left:0;width:100%;height:100%;background:#e8eaec}.io-black-mode #loading{background:#1b1d1f}
.loading-content{position:absolute;top:10%;padding:0 10px;max-width:580px;z-index:10000000}.flex{display:flex}.flex-center{align-items:center}.flex-end{display:flex;justify-content:flex-end}.flex-fill{-ms-flex:1 1 auto !important;flex:1 1 auto !important}.logo-img{text-align:center}.logo-img img{width:200px;height:auto;margin-bottom:20px}.loading-info{padding:20px;background:#fff;border-radius:10px;box-shadow:0 15px 20px rgba(18,19,20,.2)}.loading-tip{background:rgba(255,158,77,.1);border-radius:6px;padding:5px}.loading-text{color:#b22e12;font-weight:bold}.loading-topic{padding:20px 0;border-bottom:1px solid rgba(136,136,136,.2);margin-bottom:20px;font-size:12px;word-break:break-all}a{text-decoration:none}.loading-btn,.loading-btn:active,.loading-btn:visited{color:#fc5531;border-radius:5px;border:1px solid #fc5531;padding:5px 20px;transition:.3s}.loading-btn:hover{color:#fff;background:#fc5531;box-shadow:0 15px 15px -10px rgba(184,56,25,0.8)}.loading-url{color:#fc5531}.taxt-auto{color:#787a7d;font-size:14px}.auto-second{color:#fc5531;font-size:16px;margin-right:5px;font-weight:bold}
.warning-ico{width:30px;height:26px;margin-right:5px;background-image:url("data:image/svg+xml,%3Csvg class='icon' viewBox='0 0 1024 1024' xmlns='http://www.w3.org/2000/svg' width='32' height='32'%3E%3Cpath d='M872.7 582.6L635.2 177c-53.5-91.3-186.6-88.1-235.6 5.7L187.7 588.3c-46.8 89.7 18.2 197 119.4 197h449.4c104 0 168.8-112.9 116.2-202.7zM496.6 295.2c0-20.5 11.7-31.5 35.1-32.9 22 1.5 33.7 12.5 35.1 32.9V315l-26.4 267.9h-13.2L496.6 315v-19.8zm35.2 406.3c-22-1.5-34.4-13.2-37.3-35.1 1.4-19 13.2-29.3 35.1-30.7 23.4 1.5 36.6 11.7 39.5 30.7-1.5 21.9-13.9 33.6-37.3 35.1z' fill='%23f55d49'/%3E%3C/svg%3E")}
.io-black-mode .loading-info{color:#eee;background:#2b2d2f}.io-black-mode .loading-text{color:#ff8369}
@media (min-width:768px){.loading-content{min-width:450px}}
</style>
</head>
<body class="go-to">
<div id="loading">
    <style>
.loader{width:130px;height:170px;position:relative}
.loader::before,.loader::after{content:"";width:0;height:0;position:absolute;bottom:30px;left:15px;z-index:1;border-left:50px solid transparent;border-right:50px solid transparent;border-bottom:20px solid rgba(107,122,131,.15);transform:scale(0);transition:all 0.2s ease}
.loader::after{border-right:15px solid transparent;border-bottom:20px solid rgba(102,114,121,.2)}
.loader .getting-there{width:120%;text-align:center;position:absolute;bottom:0;left:-7%;font-family:"Lato";font-size:12px;letter-spacing:2px;color:#555}
.loader .binary{width:100%;height:140px;display:block;color:#555;position:absolute;top:0;left:15px;z-index:2;overflow:hidden}
.loader .binary::before,.loader .binary::after{font-family:"Lato";font-size:24px;position:absolute;top:0;left:0;opacity:0}
.loader .binary:nth-child(1)::before{content:"0";-webkit-animation:a 1.1s linear infinite;animation:a 1.1s linear infinite}
.loader .binary:nth-child(1)::after{content:"0";-webkit-animation:b 1.3s linear infinite;animation:b 1.3s linear infinite}
.loader .binary:nth-child(2)::before{content:"1";-webkit-animation:c 0.9s linear infinite;animation:c 0.9s linear infinite}
.loader .binary:nth-child(2)::after{content:"1";-webkit-animation:d 0.7s linear infinite;animation:d 0.7s linear infinite}
.loader.JS_on::before,.loader.JS_on::after{transform:scale(1)}
@-webkit-keyframes a{0%{transform:translate(30px,0) rotate(30deg);opacity:0}
100%{transform:translate(30px,150px) rotate(-50deg);opacity:1}
}@keyframes a{0%{transform:translate(30px,0) rotate(30deg);opacity:0}
100%{transform:translate(30px,150px) rotate(-50deg);opacity:1}
}@-webkit-keyframes b{0%{transform:translate(50px,0) rotate(-40deg);opacity:0}
100%{transform:translate(40px,150px) rotate(80deg);opacity:1}
}@keyframes b{0%{transform:translate(50px,0) rotate(-40deg);opacity:0}
100%{transform:translate(40px,150px) rotate(80deg);opacity:1}
}@-webkit-keyframes c{0%{transform:translate(70px,0) rotate(10deg);opacity:0}
100%{transform:translate(60px,150px) rotate(70deg);opacity:1}
}@keyframes c{0%{transform:translate(70px,0) rotate(10deg);opacity:0}
100%{transform:translate(60px,150px) rotate(70deg);opacity:1}
}@-webkit-keyframes d{0%{transform:translate(30px,0) rotate(-50deg);opacity:0}
100%{transform:translate(45px,150px) rotate(30deg);opacity:1}
}@keyframes d{0%{transform:translate(30px,0) rotate(-50deg);opacity:0}
100%{transform:translate(45px,150px) rotate(30deg);opacity:1}
}
.io-black-mode .loader .getting-there,.io-black-mode .loader .binary{color:#bbb}
</style>
<div class="loader JS_on">
	<span class="binary"></span>
	<span class="binary"></span>
	<span class="getting-there">LOADING STUFF...</span>
</div>    <div class="loading-content">
        <div class="logo-img">
            <img src=" <?php echo _g('logo');?>" alt=" <?php echo $blogname;?>" class="headerlogo">
        </div>
		<?php if($err != "1"){?>
        <div class="loading-info">
            <div class="flex flex-center loading-tip">
                <div class="warning-ico"></div><div class="loading-text">请注意您的账号和财产安全</div>
            </div>
            <div class="loading-topic">
                您即将离开 <?php $blogname; ?>，去往：<span class="loading-url"><?php echo $url;?></span>
            </div>
            <div class="flex flex-center">
                                <div class="taxt-auto"><span id="time" class="auto-second">2</span>秒后自动跳转</div>
                <script type="text/javascript">
                    delayURL();
                    function delayURL() {
                        var delay = document.getElementById("time").innerHTML;
                        var t = setTimeout("delayURL()", 2000);
                        if (delay > 0) {
                            delay--;
                            document.getElementById("time").innerHTML = delay;
                        } else {
                        clearTimeout(t);
                            window.location.href = "<?php echo $url;?>";
                        }
                    }
                </script>
                <div class="flex-fill"></div>
                <a class="loading-btn" href="<?php echo $url;?>" rel="external nofollow">继续</a>
            </div>
        </div>
		<?php }else{ ?>
		<div class="loading-info">
            <div class="flex flex-center loading-tip">
                <div class="warning-ico"></div><div class="loading-text">目标网址未通过检测</div>
            </div>
            <div class="loading-topic">
                <?php echo $title;?>
            </div>
            <div class="flex flex-center">
                                <div class="taxt-auto"><span id="time" class="auto-second">2</span>秒后自动跳转</div>
                <script type="text/javascript">
                    delayURL();
                    function delayURL() {
                        var delay = document.getElementById("time").innerHTML;
                        var t = setTimeout("delayURL()", 2000);
                        if (delay > 0) {
                            delay--;
                            document.getElementById("time").innerHTML = delay;
                        } else {
                        clearTimeout(t);
                            window.location.href = "<?php echo $url;?>";
                        }
                    }
                </script>

                <div class="flex-fill"></div>
                <a class="loading-btn" href="<?php echo $url;?>" rel="external nofollow">继续</a>
            </div>
        </div>
		<?php }?>
   </div>
    </div>
<script>
    //延时30S关闭跳转页面，用于文件下载后不会关闭跳转页的问题
    setTimeout(function() {
        window.opener = null;
        window.close();
    }, 30000);
</script>
</body>
</html>
<?php
}
function neow_article_link($link){
    if (_g('blog_page_link')==0){
        page_link_go_to($link);
    }else{
        page_link_go($link);
    }

    exit();
}
addAction('adm_writelog_head', 'contentstyle');
addAction('login_head', 'neow_Admin_style');
addAction('log_direct_link', 'neow_article_link');
