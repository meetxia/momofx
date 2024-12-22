<?php
/********
 * 版权声明：
 * 版权所有 © [天津点滴记忆信息科技有限公司]
 * 本软件受到版权法和国际版权条约的保护。未经许可，任何人不得复制、修改、发布、出售、分发本软件的任何部分。
 * 未经许可，不得将本软件用于任何商业用途。任何人不得使用本软件进行任何违法活动。
 * 未经授权使用本软件的任何行为都将受到法律追究。
 * [天津点滴记忆信息科技有限公司] 保留所有权利。
 ********/

if (!defined('EMLOG_ROOT')) {
    die('err');
}

if (!class_exists('EmAI', false)) {
    include __DIR__ . '/em_ai.php';
}

function plugin_setting_view() {

    $plugin_storage = Storage::getInstance('em_ai');
    $ak = $plugin_storage->getValue('ak');
    $sk = $plugin_storage->getValue('sk');
    $model = $plugin_storage->getValue('model');
    $temper = $plugin_storage->getValue('temper');
    $open2all = $plugin_storage->getValue('open2all');

    $defaultTemper = 0.8;
    $temper = empty($temper) || $temper > 1 || $temper <= 0 ? $defaultTemper : $temper;

    $temper_10 = $temper * 10;
    $ex1 = $ex2 = $ex3 = $ex4 = $ex5 = '';
    if ($model == 'ERNIE-Bot-turbo') {
        $ex1 = 'selected';
    }
    if ($model == 'ERNIE-Bot-4') {
        $ex2 = 'selected';
    }
    if ($model == 'ERNIE-3.5-128K') {
        $ex3 = 'selected';
    }
    if ($model == 'Meta-Llama-3-70') {
        $ex4 = 'selected';
    }
    if ($model == 'ERNIE-Speed-128K') {
        $ex5 = 'selected';
    }
    ?>
    <?php if (isset($_GET['suc'])): ?>
        <div class="alert alert-success">保存成功</div>
    <?php endif; ?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">AI 助手</h1>
    </div>
    <div class="card shadow mb-4 mt-2">
        <div class="card-body">
            <form method="post" id="ai_set" name="ai_set" action="./plugin.php?plugin=em_ai&action=setting">
                <div class="form-group">
                    <div class="alert alert-info" role="alert">
                        <h4>使用说明</h4>
                        第一步：在百度智能云平台完成个人或者企业认证，然后<a href="https://console.bce.baidu.com/qianfan/ais/console/applicationConsole/application" target="_blank">创建应用</a>，获取 API key 和 Secret Key 填入下面的设置 <br>
                        第二步：<a href="https://console.bce.baidu.com/qianfan/ais/console/onlineService" target="_blank">开通付费服务</a>，插件用到的模型包括：
                        ERNIE-Lite-8K、 ERNIE-Speed-128K、ERNIE-4.0-8K、ERNIE-3.5-128K、Meta-Llama-3-70、Stable-Diffusion-XL
                    </div>
                </div>
                <div class="form-group">
                    <label>API Key</label>
                    <input name="ak" id="ak" class="form-control" value="<?= $ak ?>" required>
                </div>
                <div class="form-group">
                    <label>Secret Key</label>
                    <input name="sk" id="sk" class="form-control" value="<?= $sk ?>" required>
                </div>
                <div class="form-group">
                    <label>模型</label>
                    <select name="model" class="form-control">
                        <option value="ERNIE-Bot-turbo" <?= $ex1 ?>>ERNIE-Lite-8K（免费）</option>
                        <option value="ERNIE-Speed-128K" <?= $ex5 ?>>ERNIE-Speed-128K（免费）</option>
                        <option value="ERNIE-Bot-4" <?= $ex2 ?>>ERNIE-4.0-8K</option>
                        <option value="ERNIE-3.5-128K" <?= $ex3 ?>>ERNIE-3.5-128K</option>
                        <option value="Meta-Llama-3-70" <?= $ex4 ?>>Meta-Llama-3-70（Meta AI）</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="formControlRange">随机性（temperature）：<span id="rangeValue"><?= $temper ?></span>，建议:0.8</label>
                    <input type="range" class="form-control-range" id="temper" name="temper" min="1" max="10" value="<?= $temper_10 ?>" oninput="updateValue(this.value)">
                    <small>较高的数值会使输出更加随机，而较低的数值会使其更加集中</small>
                </div>
                <div class="form-group">
                    <label>开放普通注册用户使用：</label>
                    <input type="checkbox" id="open2all" name="open2all" value="y" <?= $open2all === 'y' ? 'checked="checked"' : '' ?>>
                </div>
                <button type="submit" class="btn btn-success">保存</button>
            </form>
        </div>
    </div>
    <div class="card shadow mb-4 mt-2">
        <div class="card-body">
            <form method="post" id="write_set" name="write_set" action="">
                <div class="form-group">
                    <h4>自动创作文章</h4>
                    <span>根据文章分类信息自动创作一篇属于该分类的文章， 请先创建至少一个分类，并填写分类的描述和关键词</span>
                </div>
                <div class="form-group">
                    <label>自动创作接口：</label>
                    <input class="form-control" type="text" name="write_api" id="write_api" value="<?= BLOG_URL . '?plugin=em_ai&action=write&sid=0' ?>">
                </div>
                <div class="alert alert-warning">
                    <p>参数sid表示分类id，如为0则随机选取一个分类</p>
                    <p>全自动创作：打开宝塔面板 - 计划任务 - 添加任务，选择任务类型：【访问URL-GET】，配置URL为上面的自动创作接口</p>
                </div>
                <button type="submit" id="write-submit-btn" class="btn btn-success">测试创作</button>
            </form>
            <div id="message" class="mt-3"></div>
        </div>
    </div>
    <div class="card shadow mb-4 mt-2">
        <div class="card-body">
            <form method="post" id="text2img_set" name="text2img_set" action="">
                <div class="form-group">
                    <h4>图片生成 <span class="badge badge-danger">收费</span></h4>
                    <span>根据提示词生成图片，采用 Stable-Diffusion-XL 文生图模型，请先开通该模型的付费服务，计费标准：0.02元/秒，例如生成768x768尺寸的图片，通常耗时为3秒，则费用为3x0.02=0.06元</span>
                </div>
                <div class="form-group">
                    <label>提示词：</label>
                    <textarea class="form-control" name="text2img_prompt" id="text2img_prompt" placeholder="请输入提示词" required></textarea>
                    <small>如：生成一只卡通风格的猫，建议中文或者英文单词总数量不超过150个</small>
                </div>
                <button type="submit" id="text2img-submit-btn" class="btn btn-success">开始生成</button>
            </form>
            <div id="message2" class="mt-3"></div>
        </div>
    </div>
    <script>
        setTimeout(hideActived, 3600);
        $("#menu_category_ext").addClass('active');
        $("#menu_ext").addClass('show');
        $("#menu_plug_em_ai").addClass('active');

        // 提交表单
        $("#ai_set").submit(function (event) {
            event.preventDefault();
            submitForm("#ai_set");
        });

        function updateValue(value) {
            var rangeValueElement = document.getElementById('rangeValue');
            rangeValueElement.textContent = (value * 0.1).toFixed(1);
        }

        $(document).ready(function () {
            $('#write_set').on('submit', function (event) {
                event.preventDefault();
                let apiUrl = $('#write_api').val();
                $("#write-submit-btn").attr('disabled', true);
                $('#message').html('<div class="alert alert-primary"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 创作中...</div>');

                $.ajax({
                    url: apiUrl,
                    method: 'POST',
                    success: function (response) {
                        if (response.code === 0) {
                            $('#message').html('<div class="alert alert-success">创作成功！<a href="../../?post=' + response.data + '" target="_blank">查看文章</a></div>');
                        } else {
                            $('#message').html('<div class="alert alert-danger">创作失败: ' + response.msg + '</div>');
                        }
                        $("#write-submit-btn").attr('disabled', false);
                    },
                    error: function () {
                        $('#message').html('<div class="alert alert-danger">接口请求失败</div>');
                        $("#write-submit-btn").attr('disabled', false);
                    }
                });
            });
            $('#text2img_set').on('submit', function (event) {
                event.preventDefault();
                let apiUrl = "../?plugin=em_ai&action=text2img";
                $("#text2img-submit-btn").attr('disabled', true);
                $('#message2').html('<div class="alert alert-primary"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 图片生成中...</div>');

                $.ajax({
                    url: apiUrl,
                    data: {
                        prompt: $('#text2img_prompt').val()
                    },
                    method: 'POST',
                    success: function (response) {
                        if (response.code === 0) {
                            $('#message2').html('<div class="alert alert-success">图片生成成功，并已加入资源媒体库</div><div><img src="' + response.data + '""></div><div>图片地址：' + response.data + '</div>');
                        } else {
                            $('#message2').html('<div class="alert alert-danger">生成失败: ' + response.msg + '</div>');
                        }
                        $("#text2img-submit-btn").attr('disabled', false);
                    },
                    error: function () {
                        $('#message2').html('<div class="alert alert-danger">接口请求失败</div>');
                        $("#text2img-submit-btn").attr('disabled', false);
                    }
                });
            });
        });
    </script>
<?php }

function plugin_setting() {
    $ak = Input::postStrVar('ak');
    $sk = Input::postStrVar('sk');
    $model = Input::postStrVar('model');
    $temper = Input::postIntVar('temper');
    $open2all = Input::postStrVar('open2all', 'n');

    $temper = number_format($temper * 0.1, 1);

    $plugin_storage = Storage::getInstance('em_ai');
    $plugin_storage->setValue('ak', $ak);
    $plugin_storage->setValue('sk', $sk);
    $plugin_storage->setValue('model', $model);
    $plugin_storage->setValue('temper', $temper, "number");
    $plugin_storage->setValue('open2all', $open2all);

    $access_token = EmAI::getInstance()->getAccessToken();

    if (empty($access_token)) {
        Output::error('获取access_token失败, 可能是服务器网络无法访问百度API');
    }

    Output::ok();
}
