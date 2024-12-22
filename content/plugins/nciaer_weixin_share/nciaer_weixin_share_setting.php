<?php
defined('EMLOG_ROOT') || exit('access denied!');

function plugin_setting_view()
{
    $storage = Storage::getInstance('nciaer_weixin_share');
    $appid = $storage->getValue('appid');
    $appsecret = $storage->getValue('appsecret');
    $debug = $storage->getValue('debug');
    $logo = $storage->getValue('logo');
    $desc = $storage->getValue('desc');
    ?>
    <?php if (isset($_GET['succ'])): ?>
    <div class="alert alert-success">配置完成</div>
<?php endif; ?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">微信分享带图</h1>
    </div>
    <div class="card shadow mb-4 mt-2">
        <div class="card-body">
            <form method="post" action="./plugin.php?plugin=nciaer_weixin_share&action=setting">
                <div class = "alert alert-info">
                    使用说明: <br />
                    1. 请确认您的公众号已经通过认证，请确认您的域名已经备案否则无法使用;<br />
                    2. 请在微信公众号后台获取appid和appsecret，设置IP白名单，设置js安全域名
                </div>
                <div class="form-group">
                    <label>AppID</label>
                    <input name="appid" class="form-control" style="width: 300px;" value="<?= $appid; ?>"
                           required="required"/>
                </div>
                <div class="form-group">
                    <label>AppSecret:</label>
                    <input name="appsecret" class="form-control" style="width: 300px;" value="<?= $appsecret; ?>" required="required"/>
                </div>
                <div class = "form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="debug" <?= $debug == 1 ? 'checked="checked"':'';?>>
                    <label>开启调试模式 (如果分享异常时请打开排查错误)</label>
                </div>
                <div class="form-group">
                    <label>默认分享图:</label>
                    <input name="logo" class="form-control" style="width: 300px;" value="<?= $logo; ?>" required="required"/>
                    <div>
                        文章无封面或者分享其他页面时使用
                    </div>
                </div>
                <div class="form-group">
                    <label>默认描述:</label>
                    <textarea name="desc" class="form-control" style="width: 500px; height: 150px;"><?=$desc; ?></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-success btn-sm mx-2" value="保存">
                </div>
            </form>
        </div>
    </div>
    <script>
        setTimeout(hideActived, 3600);
        $("#menu_category_ext").addClass('active');
        $("#menu_ext").addClass('show');
        $("#menu_plug").addClass('active');
    </script>
<?php }

function plugin_setting()
{
    $appid = Input::postStrVar('appid');
    $appsecret = Input::postStrVar('appsecret');
    $debug = Input::postIntVar('debug');
    $logo = Input::postStrVar('logo');
    $desc = Input::postStrVar('desc');
    $storage = Storage::getInstance('nciaer_weixin_share');
    $storage->setValue('appid', $appid);
    $storage->setValue('appsecret', $appsecret);
    $storage->setValue('debug', $debug);
    $storage->setValue('logo', $logo);
    $storage->setValue('desc', $desc);
    emDirect('./plugin.php?plugin=nciaer_weixin_share&succ=1');
}
