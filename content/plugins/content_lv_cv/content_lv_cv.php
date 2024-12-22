<?php
/**
* Plugin Name:文章内容隐藏
* Version: 2.4
* Author: 路羽
* Author URL: https://www.luyuz.cn
* Plugin URL: https://www.luyuz.cn/emlogpro-content-hide.html
* Description: 可以根据管理员后台编辑器中的设置来隐藏文章的内容，通过阅读用户的评论、登录、密码、公众号验证码来解锁文章隐藏内容，更多说明请访问插件链接。
*/
if(!defined('EMLOG_ROOT')) {exit('error!');}


function content_lv_cv_side(){
    $pluginName = isset($_GET['plugin']) ? addslashes(trim($_GET['plugin'])) : '';
    $isActive = $pluginName == 'content_lv_cv';
	if ($isActive) {
		echo '<script>$("#menu_ext").addClass("show");</script>';
	}
	echo '<a class="collapse-item '. ( $isActive ? 'active' : '') .'" href="./plugin.php?plugin=content_lv_cv">文章内容隐藏</a>';
}

function content_lv_cv_user($logid){
	if (!empty($_SESSION['nickname'])){
		$db=Database::getInstance();
		$sql = "SELECT cid FROM " . DB_PREFIX . "comment WHERE gid='".$logid."' and poster='".$_SESSION['nickname']."' LIMIT 1";
		$row = $db -> once_fetch_array($sql);
		if ($row) return 1;
	}
}

function content_lv_cv_x(){
    //$content = ob_get_clean();
	$content = '';
	$content = preg_replace("/\[[c|l|u|s|w|m|d]v\](.*?)\[\/[c|l|u|s|w|m|d]v\]/is", '', $content);
	ob_start(); 
	echo $content;

}

function content_lv_cv($logData){
    $content_lv_cv = Storage::getInstance('content_lv_cv');
	$content_img = $content_lv_cv->getValue('content_img');
	$content_dis = $content_lv_cv->getValue('content_dis');
	$content_css = $content_lv_cv->getValue('content_css');
	$content_urlo = $content_lv_cv->getValue('content_urlo');
	$content_urlt = $content_lv_cv->getValue('content_urlt');
	$c_wxtoken = $content_lv_cv->getValue('c_wxtoken');
	$c_wxqr = $content_lv_cv->getValue('c_wxqr');
	$c_tips = $content_lv_cv->getValue('c_tips');
				
	if(preg_match('/\[[c|l|u|s|w|m|d]v\](.*?)\[\/[c|l|u|s|w|m|d]v\]/is', $logData['log_content'])){
		$logid = $logData['logid'];
		$content = ob_get_clean();
		$css ='<style type="text/css">
		.cl_content{'.$content_css
		.'}.cl_content input.euc-y-i[type="password"]{background:#fff;width:40%;line-height:30px;margin-top:5px;border-radius:3px;outline:medium;border:0;}.cl_content input.euc-y-s[type="submit"]{margin-left:0px;margin-top:5px;width:20%;line-height:30px;border-radius:0 3px 3px 0;background-color:#3498db;color:#fff;box-shadow:none;outline:medium;text-align:center;-moz-box-shadow:none;box-shadow:none;border:0;height:auto;}input.euc-y-s[type="submit"]:hover{background-color:#5dade2;}.hide-tit{text-align:center;border-bottom:1px;margin-bottom:10px}.luyu-yzm .success.cm-btn{color:#fff;border:none}.luyu-yzm .primary.cm-alert{background-color:#7db1f1;color:#fff}.luyu-yzm .success.cm-alert,.luyu-yzm .success.cm-btn,.luyu-yzm button.success{background-color:#3498db;}.luyu-yzm .cm-alert{border-radius:3px;padding:15px;margin-bottom:15px;background-color:#fff}.luyu-yzm .cm-btn,.luyu-yzm button{background-color:#fff;padding:15px 20px;border-radius:3px}.luyu-yzm .cm-card>.cm-card-body{padding:15px}.luyu-yzm .cm-card{overflow:hidden;margin-bottom:15px;border-radius:3px}.luyu-yzm .cm-text-primary{color:#7db1f1}.luyu-yzm .cm-text-success{color:#3498db;}.luyu-yzm .cm-grid{padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}.luyu-yzm .cm-grid .cm-row{margin-left:-15px;margin-right:-15px}.luyu-yzm .cm-grid .cm-row [class*=cm-col]{position:relative;padding-left:15px;padding-right:15px}@media (min-width:768px){.luyu-yzm .cm-grid .cm-row .cm-col-md-1{float:left;width:8.33333%}.luyu-yzm .cm-grid .cm-row .cm-col-md-2{float:left;width:16.66666%}.luyu-yzm .cm-grid .cm-row .cm-col-md-3{float:left;width:25%}.luyu-yzm .cm-grid .cm-row .cm-col-md-4{float:left;width:33.33333%}.luyu-yzm .cm-grid .cm-row .cm-col-md-5{float:left;width:41.66666%}.luyu-yzm .cm-grid .cm-row .cm-col-md-6{float:left;width:50%}.luyu-yzm .cm-grid .cm-row .cm-col-md-7{float:left;width:58.33333%}.luyu-yzm .cm-grid .cm-row .cm-col-md-8{float:left;width:66.66666%}.luyu-yzm .cm-grid .cm-row .cm-col-md-9{float:left;width:75%}.luyu-yzm .cm-grid .cm-row .cm-col-md-10{float:left;width:83.33333%}.luyu-yzm .cm-grid .cm-row .cm-col-md-11{float:left;width:91.66666%}.luyu-yzm .cm-grid .cm-row .cm-col-md-12{float:left;width:100%}}.luyu-yzm input[type=text]{padding:15px;border:1px solid #dadada;border-radius:3px;color:black;width:100%}.luyu-yzm .cm-resp-img{width:100%;height:auto}.luyu-yzm .cm-hide{display:none}.luyu-yzm .cm-grid{width:86%}</style>';
		$content =$content.$css;
		if(preg_match('/\[cv\](.*?)\[\/cv\]/is', $content,$temp)){
			if(content_lv_cv_user($logid) || isset($_COOKIE["comment_cv_".$logid])){
				$content = preg_replace("/\[cv\](.*?)\[\/cv\]/is", '
				\1', $content);
			}else{
				$content = preg_replace("/\[cv\](.*?)\[\/cv\]/is", '<div class="cl_content"><img src='.$content_img.' alt="文章回复.png" style="display:'.$content_dis.';vertical-align: middle;height: 20px;width: 20px;">管理员已设置<a href='.$content_urlo.'>评论</a>后刷新可查看</div>', $content);
			}
		}
		if(preg_match("/\[lv\](.*?)\[\/lv\]/is", $content,$temp)){
			if(ISLOGIN){
				$content = preg_replace("/\[lv\](.*?)\[\/lv\]/is", '\1', $content);
			}else{
				$content = preg_replace("/\[lv\](.*?)\[\/lv\]/is", '
				<div class="cl_content"><img src='.$content_img.' alt="文章回复.png" style="display:'.$content_dis.';vertical-align: middle;height: 20px;width: 20px;">管理员已设置<a href='.$content_urlt.'>登录</a>后刷新可查看</div>', $content);
			}
		}
		
		if(preg_match("/\[uv\](.*?)\[\/uv\]/is", $content,$temp)){
			if( ISLOGIN == true && isset($_COOKIE["comment_cv_".$logid])){
				$content = preg_replace("/\[uv\](.*?)\[\/uv\]/is", '
				\1', $content);
			}else{
			    if(ISLOGIN){
			    $content = preg_replace("/\[uv\](.*?)\[\/uv\]/is", '
				<div class="cl_content"><img src='.$content_img.' alt="文章回复.png" style="display:'.$content_dis.';vertical-align: middle;height: 20px;width: 20px;">管理员已设置<a href='.$content_urlo.'>登录评论</a>后刷新可查看</div>', $content);
			    }else{
				$content = preg_replace("/\[uv\](.*?)\[\/uv\]/is", '
				<div class="cl_content"><img src='.$content_img.' alt="文章回复.png" style="display:'.$content_dis.';vertical-align: middle;height: 20px;width: 20px;">管理员已设置<a href='.$content_urlt.'>登录评论</a>后刷新可查看</div>', $content);}
			}
		}
		
		preg_match("/\[sv\](.*?)\[\/sv\]/is", $content, $temp);
		preg_match_all('|\[key\]([\s\S]*?)\[\/key\]|i', $content, $hide_words);
		if (isset($_POST['e_secret_key']) && $_POST['e_secret_key'] == $hide_words[1][0]) {
		    $content = preg_replace("/\[sv\](.*?)\[\/sv\]/is", '\1', $content);
		    $content = preg_replace("|\[key\]([\s\S]*?)\[\/key\]|i", '', $content);
		    
		} else {
		    $content = preg_replace("|\[key\]([\s\S]*?)\[\/key\]|i", '', $content);
		    $content = preg_replace("/\[sv\](.*?)\[\/sv\]/is", '
		    <script src="https://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script><form class="cl_content" method="post" name="e-secret"><input type="password" name="e_secret_key" placeholder="输入密码查看" class="euc-y-i" maxlength="50"><input type="submit" class="euc-y-s" value="确定"><div class="euc-clear"></div></form>', $content);
		}
		
		
		if(preg_match("/\[wv\](.*?)\[\/wv\]/is", $content,$temp)){
		   	$weixin_cookie = md5($c_wxtoken. 'wxsecret_code_luyu' .'luyu');
		   	$_weixin_cookie = isset( $_COOKIE['wxsecret_code_luyu'] ) ? $_COOKIE['wxsecret_code_luyu'] : '';
			if($_weixin_cookie == $weixin_cookie){
				$content = preg_replace("/\[wv\](.*?)\[\/wv\]/is", '\1', $content);
			}else{
				$content = preg_replace("/\[wv\](.*?)\[\/wv\]/is", '<script src="https://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script><div class="cl_content"><div class="luyu-yzm"><div class="cm-grid cm-card secret_view"><div class="cm-row"><div class="cm-col-md-4"><img src="'.$c_wxqr.'"class="cm-resp-img"></div><div class="cm-col-md-8"><div class="hide_content_info"><div class="hide-tit">管理员设置了验证码查看</div><div class="cm-alert primary">扫码二维码或搜索【'.$c_tips.'】发送【验证码】</div><div style="display: flex"><input id="captcha_input"type="text"placeholder="输入验证码并确认">&nbsp;&nbsp;<input id="check_secret_view"class="cm-btn success"type="button"value="确认"></div></div></div></div></div></div></div><script>$("#check_secret_view").click(function(){var captcha=$("#captcha_input").val();if(captcha==""){alert("请填写验证码后再确认！");return false}else{var ajax_data={captcha:captcha};$.post("' . BLOG_URL . 'content/plugins/content_lv_cv/wxapi.php?captcha=check_captcha",ajax_data,function(c){c=$.trim(c);if(c=="200"){location.reload()}else if(c=="300"){alert("您的验证码错误，请重新申请")}else if(c=="400"){alert("您的验证码过期，请重新申请")}else{alert("您的验证码错误，请重新申请")}})}});</script>', $content);
			}
		}
		
    	if(preg_match("/\[mv\](.*?)\[\/mv\]/is", $content,$temp)){
			if(isset($_POST['more'])){
				$content = preg_replace("/\[mv\](.*?)\[\/mv\]/is", '\1', $content);
			}else{
				$content = preg_replace("/\[mv\](.*?)\[\/mv\]/is", '<script src="https://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script><form class="cl_content" method="post" name="lvcv_more"><input type="submit"  name="more" class="euc-y-s" value="查看更多"><div class="euc-clear"></div></form>', $content);
			}
		}
		
		if (preg_match_all("/\[dv\](.*?)\[\/dv\]/is", $content, $matches)) {
		foreach ($matches[1] as $innerContent) {
		    preg_match_all('/https?:\/\/[\w\-\.]+(:\d+)?(\/[\w\-\.\/\?\@\%\!\&=\+\~\:\#\;\,]*)?/', $innerContent, $links);
		    if (!empty($links[0])) {
		        $uniqueLinks = array_unique($links[0]);
		        $linkHtml = implode('', $uniqueLinks); 
		        $linkHtml = trim($linkHtml);
		        $replacement = '<style>.cl_down{padding:6px;border:1px solid#5298ff;border-radius:5px;color:#166df1;text-align:center;font-size:15px;line-height:33px;display:inline-block}</style><form class="cl_content"><a class="cl_down"href="'.$linkHtml.'"target="_blank"rel="nofollow">点击下载</a><div class="euc-clear"></div></form>';
		        $pattern = "/\[dv\]" . preg_quote($innerContent, '/') . "\[\/dv\]/is";
		        $content = preg_replace($pattern, $replacement, $content); }
		}
		}
		
		ob_start();
		echo $content;
	}
}

function comment_saved_cv(){
	setcookie("comment_cv_".$_POST['gid'], "1", time()+259200, "/");
}

function content_lv_cv_Button(){
	echo '<style>.content_lv-div{display:none;border:2px dashed #4e73df;border-radius:6px;padding:10px;margin-top:10px;}.content_lv-div  a{margin-bottom:5px;color:#4e73df!important;}.content_lv-div small{text-align:right;display:block;}</style>
	<script>
    $(document).ready(function(){var uploadLink = $(\'a[href="#mediaModal"]\');var newLink = $(\'<a href="#" class="ml-3" id="newLink">文章内容隐藏</a>\');uploadLink.after(newLink);var content_lvDiv = $(\'<div class="content_lv-div">\' +
            \'<p><a id="Button_lv">登录</a>&nbsp;|&nbsp;<a id="Button_cv">评论</a>&nbsp;|&nbsp;<a id="Button_uv">登评</a>&nbsp;|&nbsp;<a id="Button_sv">密码</a>&nbsp;|&nbsp;<a id="Button_wv">微信</a>&nbsp;|&nbsp;<a id="Button_dv">下载</a>&nbsp;|&nbsp;<a id="Button_mv">更多</a>\' +
            \'<small>由文章内容隐藏插件提供</small>\' +
            \'</div>\');newLink.after(content_lvDiv);newLink.on(\'click\',function (){content_lvDiv.slideToggle();});$(\'#gen_article\').on(\'click\',function(){generateArticle("$access_token");});$(\'#refine_article\').on(\'click\',function(){refineArticle("$access_token");});});</script>
	<script type="text/javascript" src=" '. BLOG_URL .'content/plugins/content_lv_cv/assets/content_lv.js"></script>';
}

addAction('adm_menu_ext', 'content_lv_cv_side');
addAction('adm_writelog_head', 'content_lv_cv_Button');
addAction('index_head','content_lv_cv_x');
addAction('log_related','content_lv_cv');
addAction('comment_saved', 'comment_saved_cv');

