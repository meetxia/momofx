<?php
/**
* Plugin Name: 文章内容隐藏
* Author: 路羽
* Plugin URL: https://www.luyuz.cn/emlogpro-content-hide.html
* Description: 可以根据管理员后台编辑器中的设置来隐藏文章的内容，通过阅读用户的评论、登录、密码、公众号验证码来解锁文章隐藏内容，更多说明请访问插件链接。
*/

!defined('EMLOG_ROOT') && exit('access deined!');

function plugin_setting_view() {
	$content_lv_cv = Storage::getInstance('content_lv_cv');
	$content_img = $content_lv_cv->getValue('content_img');
	$content_dis = $content_lv_cv->getValue('content_dis');
	$content_css = $content_lv_cv->getValue('content_css');
	$content_urlo = $content_lv_cv->getValue('content_urlo');
	$content_urlt = $content_lv_cv->getValue('content_urlt');
	$c_wxtoken = $content_lv_cv->getValue('c_wxtoken');
	$c_wxqr = $content_lv_cv->getValue('c_wxqr');
	$c_tips = $content_lv_cv->getValue('c_tips');
	$c_maxage = $content_lv_cv->getValue('c_maxage');
?>


<?php if(isset($_GET['setting'])):?>
<div class="actived alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>插件设置完成</div>
<?php endif;?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800">
		文章内容隐藏
		</span>
	</h1>
</div>
<div class="row">
	<div class="col-lg-8">
		<div class="card shadow mb-4">
			<div class="card-header card-header-menu">
				<div>
					隐藏显示配置
				</div>
					<a id='ver' ></a>
			</div>
			<div class="card-body">
					 <div class="panel-heading" id="tabs-150137">
				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="nav-link active" href="#panel-450728" data-toggle="tab">基本设置</a>
					</li>
					<li>
						<a  class="nav-link" href="#panel-268909" data-toggle="tab">公众号隐藏设置</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="panel-450728">
						<form  action="plugin.php?plugin=content_lv_cv&action=setting"method="post">
					<div class="form-group">
						<label>
							隐藏前图片是否显示：
						</label>
						<select name="content_dis" id="content_dis" class="form-control" style="width: 50%;">
							<option value="inline-block" <?php if($content_dis=="inline-bloc" ) echo"selected"; ?>>显示
							</option>
							<option value="none" <?php if($content_dis=="none" ) echo "selected";?>>隐藏
							</option>
						</select>
					</div>
					<div class="form-group">
						<label>
							隐藏前图片：
						</label>
						<input class="form-control" id="content_img" name="content_img" rows="4"style="width: 90%;" value="<?php echo $content_img;?>">
					</div>
					<div class="form-group">
						<label>
							评论跳转地址：
						</label>
						<input class="form-control" id="content_urlo" name="content_urlo" rows="4"style="width: 90%;" value="<?php echo $content_urlo;?>">
					</div>
					<div class="form-group">
						<label>
							登录跳转地址：
						</label>
						<input class="form-control" id="content_urlt" name="content_urlt" style="width: 90%;"rows="4" value="<?php echo $content_urlt;?>">
					</div>
					<div class="form-group">
						<label>
							显示样式：
						</label>
						<textarea class="form-control" id="content_css" name="content_css" rows="2"	placeholder="这里可以编写自己的隐藏块的显示样式，当然也可以使用我们默认的"><?php echo $content_css;?>
						</textarea>
					</div>
					<button type="submit" class="btn btn-large btn-success">
						保存设置
					</button>
				
					</div>
					<div class="tab-pane" id="panel-268909">
						
					<div class="form-group">
						<label>
							加密TOKEN设置（不少于3个字符）：
						</label>
						<input class="form-control" id="c_wxtoken" name="c_wxtoken" rows="4"style="width: 90%;" value="<?php echo $c_wxtoken;?>">
					</div>
					<div class="form-group">
						<label>
							解锁后有效期（默认表示3天有效）：
						</label>
						<input type="number" class="form-control" id="c_maxage" name="c_maxage" rows="4"style="width: 90%;" value="<?php echo $c_maxage;?>">
					</div>
					<div class="form-group">
						<label>
							公众号关注图片：
						</label>
						<input class="form-control" id="c_wxqr" name="c_wxqr" rows="4"style="width: 90%;" value="<?php echo $c_wxqr;?>">
					</div>
					<div class="form-group">
						<label>
							公众号搜索提示：
						</label>
						<input class="form-control" id="c_tips" name="c_tips" style="width: 90%;"rows="4" value="<?php echo $c_tips;?>">
					</div>
					<div class="form-group">
						<label>
							公众号验证码获取页（单独打开无效！）：
						</label>
						<div class="input-group">
                <input type="text" class="form-control"  value='<a href="<?= BLOG_URL ?>content/plugins/content_lv_cv/wxapi.php?url_captcha=get_captcha">查看验证码</a>'>
                <div class="input-group-append">
                    <button class="btn btn-outline-success" type="button" onclick="window.location.href='<?= BLOG_URL ?>content/plugins/content_lv_cv/wxapi.php?url_captcha=get_captcha'">
                        请在公众号内打开链接
                    </button>
                </div>
					</div>
					 <label>设置步骤：内容与互动-关键词回复-自动回复-添加回复-复制以上内容设置回复内容</label>
            </div>
					<button type="submit" class="btn btn-large btn-success">
						保存设置
					</button>
				</form>
					</div>
				</div>
			</div>
			
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card shadow mb-4">
			<div class="card-header">
				当前样式预览及说明
			</div>
			<style type="text/css">
				<?php echo '.cl_content{'. $content_css. '}.cl_content input.euc-y-i[type="password"]{background:#fff;width:40%;line-height:30px;margin-top:5px;border-radius:3px;outline:medium;border:0;}.cl_content input.euc-y-s[type="submit"]{margin-left:0px;margin-top:5px;width:20%;line-height:30px;border-radius:0 3px 3px 0;background-color:#3498db;color:#fff;box-shadow:none;outline:medium;text-align:center;-moz-box-shadow:none;box-shadow:none;border:0;height:auto;}input.euc-y-s[type="submit"]:hover{background-color:#5dade2;}.'?>
			</style>
			<div class="cl_content">
				<img src="<?php echo $content_img?>" alt="文章回复.png" style="display:<?php echo $content_dis?>;vertical-align: middle;height: 20px; width: 20px;">管理员已设置<a href="#comment-post">	评论</a>	后刷新可查看
			</div>
			<div style="padding:6px;">
			</div>
			<div class="cl_content">
				<img src=<?php echo $content_img?>	alt="文章回复.png" style="display:	<?php echo $content_dis?>;vertical-align: middle;height: 20px; width: 20px;">管理员已设置	<a href=<?php echo $content_urlt?>>
						登录	</a>	后刷新可查看
			</div>
			<div style="padding:6px;">
			</div>
			<form class="cl_content" method="post" name="e-secret"><input type="password" name="e_secret_key" placeholder="输入密码查看" class="euc-y-i" maxlength="50"><input type="submit" class="euc-y-s" value="确定"><div class="euc-clear"></div></form>
			<div class="card-body" id="r_description">
				<p>
					正在获取最新样式及说明 ...
				</p>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript"  src=/content/plugins/content_lv_cv/assets/content_lv.js></script>

<?php }function plugin_setting(){
	$plugin_storage = Storage::getInstance('content_lv_cv');
	$plugin_storage->setValue('content_img', isset($_POST['content_img']) ? addslashes(trim($_POST['content_img'])) : '');
	$plugin_storage->setValue('content_dis', isset($_POST['content_dis']) ? addslashes(trim($_POST['content_dis'])) : '');
	$plugin_storage->setValue('content_css', isset($_POST['content_css']) ? addslashes(trim($_POST['content_css'])) : '');
	$plugin_storage->setValue('content_urlo', isset($_POST['content_urlo']) ? addslashes(trim($_POST['content_urlo'])) : '');
	$plugin_storage->setValue('content_urlt', isset($_POST['content_urlt']) ? addslashes(trim($_POST['content_urlt'])) : '');
	$plugin_storage->setValue('c_wxtoken', isset($_POST['c_wxtoken']) ? addslashes(trim($_POST['c_wxtoken'])) : '');
	$plugin_storage->setValue('c_wxqr', isset($_POST['c_wxqr']) ? addslashes(trim($_POST['c_wxqr'])) : '');
	$plugin_storage->setValue('c_tips', isset($_POST['c_tips']) ? addslashes(trim($_POST['c_tips'])) : '');
	$plugin_storage->setValue('c_maxage', isset($_POST['c_maxage']) ? addslashes(trim($_POST['c_maxage'])) : '');
}?>

