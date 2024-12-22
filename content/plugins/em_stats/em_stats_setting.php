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

if (!class_exists('EmStats', false)) {
    include __DIR__ . '/em_stats.php';
}

function plugin_setting_view() {

    $data = EmStats::getInstance()->getViews();
    $labels = $data['labels'];
    $datasets = $data['datasets'];

    $articles = EmStats::getInstance()->getArticlesByViews();
    $todayArticles = EmStats::getInstance()->getTodayArticlesByViews();
    $weekArticles = EmStats::getInstance()->getWeekArticlesByViews();

    $ex1 = '';
    
    $plugin_storage = Storage::getInstance('em_stats');
    $skip_spider = $plugin_storage->getValue('skip_spider');

    if ($skip_spider == 1) {
        $ex1 = 'checked="checked"';
    }

    ?>
    <?php if (isset($_GET['suc'])): ?>
        <div class="alert alert-success">保存成功</div>
    <?php endif; ?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">数据统计</h1>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4 mt-2">
                <h6 class="card-header">最近30天文章阅读量变化趋势</h6>
                <div class="card-body" id="admindex_msg">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4 mt-2">
                <h6 class="card-header">最近30天文章阅读量排行</h6>
                <div class="card-body" id="admindex_msg">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($articles as $v): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="<?= Url::log($v['gid']) ?>" target="_blank"><?= $v['title'] ?></a>
                                <span class="small"><?= $v['total_views'] ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4 mt-2">
                <h6 class="card-header">今天文章阅读量排行</h6>
                <div class="card-body" id="admindex_msg">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($todayArticles as $v): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="<?= Url::log($v['gid']) ?>" target="_blank"><?= $v['title'] ?></a>
                                <span class="small"><?= $v['total_views'] ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4 mt-2">
                <h6 class="card-header">最近7天文章阅读量排行</h6>
                <div class="card-body" id="admindex_msg">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($weekArticles as $v): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="<?= Url::log($v['gid']) ?>" target="_blank"><?= $v['title'] ?></a>
                                <span class="small"><?= $v['total_views'] ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div>
        提示：数据从插件启动开始统计，过去的阅读量无法计入，仅保留最近30天数据。
        <hr>
        <form method="post" id="stats_set">
            <div class="form-group form-check">
                <input size="10" name="skip_spider" type="checkbox" class="form-check-input" id="skip_spider" value="1" <?= $ex1 ?>>
                <input size="10" name="aaa" type="hidden" id="aa" value="1">
                <label class="form-check-label" for="skip_spider">尽量过滤掉机器人（蜘蛛）的访问</label>
            </div>
        </form>
    </div>
    <script>
        setTimeout(hideActived, 3600);
        $("#menu_category_ext").addClass('active');
        $("#menu_ext").addClass('show');
        $("#menu_plug_em_stats").addClass('active');
    </script>
    <script src="//cdn.staticfile.net/Chart.js/4.2.1/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($labels)?>,
                datasets: [{
                    label: '阅读量',
                    data: <?= json_encode($datasets)?>,
                    borderWidth: 2,
                    tension: 0.4, // 设置曲线弧度
                    borderColor: "rgba(68,190,190,1)", // 线条颜色
                    fill: true, // 填充颜色
                    backgroundColor: "rgba(68,190,190,.3)"// 线条下方的填充颜色
                }]
            },
            options: {
                responsive: true, // 自适应尺寸
                maintainAspectRatio: true, // 不保持比例
                width: 400,
                height: 400,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
            }
        });

        $('#skip_spider').change(function () {
            $('#stats_set').submit(); // 当复选框改变时提交表单
        });
    </script>
<?php }

if ($_POST) {
    $skip_spider = Input::postIntVar('skip_spider');

    $plugin_storage = Storage::getInstance('em_stats');
    $plugin_storage->setValue('skip_spider', $skip_spider);

    header('Location:./plugin.php?plugin=em_stats&suc=1');
}
