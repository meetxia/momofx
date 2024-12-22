<?php
if (!defined('EMLOG_ROOT')) {
    die('err');
}

$do = Input::getStrVar('do');
$img = Input::getStrVar('img');

if ($do === 'rm') {
    $plugin_storage = Storage::getInstance('loginColor');
    $my_bg_img = $plugin_storage->getValue('my_bg_img');
    $key = array_search($img, $my_bg_img);
    if ($key !== false) {
        unset($my_bg_img[$key]);
        $plugin_storage->setValue('my_bg_img', $my_bg_img, 'array');
    }
    header('Location:./plugin.php?plugin=loginColor&suc=1');
    exit;
}

function plugin_setting_view() {

    $plugin_storage = Storage::getInstance('loginColor');
    $cur_bg_img = $plugin_storage->getValue('cur_gb_img');
    $my_bg_img = $plugin_storage->getValue('my_bg_img');

    $defaultImages = [
        '../content/plugins/loginColor/imgs/bg1.jpg',
        '../content/plugins/loginColor/imgs/bg2.jpg',
        '../content/plugins/loginColor/imgs/bg3.jpg',
    ];

    $ex1 = $ex2 = '';
    $is_rand = $plugin_storage->getValue('is_rand');
    if ($is_rand == 1) {
        $ex1 = 'checked="checked"';
    }

    $bing_img = $plugin_storage->getValue('bing_img');
    if ($bing_img == 1) {
        $ex2 = 'checked="checked"';
    }

    ?>
    <?php if (isset($_GET['succ'])): ?>
        <div class="alert alert-success">保存成功</div>
    <?php endif; ?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">登录背景图</h1>
    </div>
    <div class="card shadow mb-4 mt-2">
        <div class="card-body">
            <div class="form-group">
                <h5>当前背景图片</h5>
                <?php if (!empty($cur_bg_img)): ?>
                    <img src="<?= $cur_bg_img ?>" style="max-width: 200px;">
                <?php else: ?>
                    <p>未设置</p>
                <?php endif; ?>
            </div>
            <hr>
            <form method="post" action="./plugin.php?plugin=loginColor&action=setting" enctype="multipart/form-data">
                <div class="form-group">
                    <h5 for="selectBackgroundImage">系统默认背景图</h5>
                    <div class="row">
                        <?php foreach ($defaultImages as $v):
                            $checked = $v == $cur_bg_img ? 'checked' : '';
                            ?>
                            <div class="col-md-4">
                                <img src="<?= $v ?>" class="img-thumbnail mb-2">
                                <label>
                                    <input type="radio" name="selectedBackground" <?= $checked ?> value="<?= $v ?>"> 选择
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <hr>
                    <h5>自定义背景图</h5>
                    <div class="row">
                        <?php
                        if ($my_bg_img) {
                            foreach ($my_bg_img as $v):
                                $checked = $v == $cur_bg_img ? 'checked' : '';
                                ?>
                                <div class="col-md-4">
                                    <img src="<?= $v ?>" class="img-thumbnail mb-2">
                                    <label>
                                        <input type="radio" name="selectedBackground" <?= $checked ?> value="<?= $v ?>"> 选择
                                    </label>
                                    <a href="./plugin.php?plugin=loginColor&do=rm&img=<?= $v ?>" class="badge badge-danger">删除</a>
                                </div>
                            <?php endforeach;
                        } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="newBackgroundImage">上传自定义背景图（推荐尺寸： 1920x900 像素）</label>
                    <input type="file" class="form-control-file" id="newBackgroundImage" name="newBackgroundImage">
                </div>
                <div class="form-group form-check">
                    <input size="10" name="is_rand" type="checkbox" class="form-check-input" id="is_rand" value="1" <?= $ex1 ?>>
                    <label class="form-check-label" for="is_rand">随机背景图，从自定义背景图中随机展示一张</label>
                </div>
                <hr>
                <div class="form-group form-check">
                    <input size="10" name="bing_img" type="checkbox" class="form-check-input" id="bing_img" value="1" <?= $ex2 ?>>
                    <label class="form-check-label" for="is_rand">使用bing壁纸</label>
                </div>
                <input type="hidden" name="a" value="123">
                <hr>
                <button type="submit" class="btn btn-primary">保存</button>
            </form>
        </div>
    </div>
    <script>
        setTimeout(hideActived, 3600);
        $("#menu_category_ext").addClass('active');
        $("#menu_ext").addClass('show');
        $("#menu_plug").addClass('active');
    </script>
    <?php
}

function plugin_setting() {
    $img = Input::postStrVar('selectedBackground');
    $is_rand = Input::postIntVar('is_rand');
    $bing_img = Input::postIntVar('bing_img');

    $plugin_storage = Storage::getInstance('loginColor');

    // 设置当前背景图
    $plugin_storage->setValue('cur_gb_img', $img);

    // 设置随机背景图
    $plugin_storage->setValue('is_rand', $is_rand);

    $plugin_storage->setValue('bing_img', $bing_img);

    $attach = isset($_FILES['newBackgroundImage']) ? $_FILES['newBackgroundImage'] : '';
    if ($attach && $attach['error'] !== 4) {
        upload2local($attach, $ret);
        if (empty($ret['success'])) {
            emMsg('图片上传失败');
        }

        // 更新当前背景图
        $newImg = $ret['file_info']['file_path'];

        // 更新自定义背景列表
        $my_bg_img = $plugin_storage->getValue('my_bg_img');
        if (empty($my_bg_img) || !is_array($my_bg_img)) {
            $my_bg_img = [];
        }
        $my_bg_img[] = $newImg;
        $plugin_storage->setValue('my_bg_img', $my_bg_img, 'array');
    }

    emDirect('./plugin.php?plugin=loginColor&succ=1');
}
