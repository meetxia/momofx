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

if (!class_exists('EmCrawler', false)) {
	include __DIR__ . '/em_crawler.php';
}

function plugin_setting_view() {
	$crawler = Input::getStrVar('crawler');
	$data = EmCrawler::getInstance()->getList($crawler);
	?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">爬虫记录插件</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4 mt-2">
                <h6 class="card-header">最近7天爬虫来访记录</h6>
                <div class="row mx-3 mt-3 justify-content-between">
                    <div class="form-inline">
                        <div id="f_t_sort" class="mx-1">
                            <select name="bysort" id="bysort" onChange="selectCrawler(this);" class="form-control">
                                <option value="" <?php if ($crawler == "") echo 'selected' ?>>仅查看爬虫</option>
                                <option value="baidu" <?php if ($crawler == "baidu") echo 'selected' ?>>百度</option>
                                <option value="google" <?php if ($crawler == "google") echo 'selected' ?>>Google</option>
                                <option value="sogou" <?php if ($crawler == "sogou") echo 'selected' ?>>搜狗</option>
                                <option value="bing" <?php if ($crawler == "bing") echo 'selected' ?>>必应bing</option>
                                <option value="360" <?php if ($crawler == "360") echo 'selected' ?>>360搜索</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="admindex_msg">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="dataTable">
                            <thead>
                            <tr>
                                <th>爬虫名称</th>
                                <th>UA及详情</th>
                                <th>访问URL</th>
                                <th>来访时间</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
							foreach ($data as $v):?>
                                <tr>
                                    <td><?= $v['name'] ?></td>
                                    <td><?= $v['ua'] ?></td>
                                    <td><a href="<?= $v['url'] ?>" target="_blank"><?= $v['url'] ?></a></td>
                                    <td><?= $v['date'] ?></td>
                                </tr>
							<?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        提示：从插件启动开始记录，本页仅展示最近7天不超过800条数据。
    </div>
    <script>
        setTimeout(hideActived, 3600);
        $("#menu_category_ext").addClass('active');
        $("#menu_ext").addClass('show');
        $("#menu_plug_em_crawler").addClass('active');

        function selectCrawler(obj) {
            window.open("./plugin.php?plugin=em_crawler&crawler=" + obj.value, "_self");
        }

    </script>
<?php }
