<?php
!defined('EMLOG_ROOT') && exit('access denied!');

function plugin_setting_view() {
    extract(sitemap_config());
    $ex1 = $ex2 = '';
    if ($sitemap_show_footer) {
        $ex1 = 'checked="checked" ';
    }
    if ($sitemap_comment_time) {
        $ex2 = 'checked="checked" ';
    }
    $sitemap_url = BLOG_URL . $sitemap_name;
    ?>
    <?php if (isset($_GET['setting'])): ?>
        <div class="alert alert-success">插件设置完成</div><?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">插件设置失败</div><?php endif; ?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">站点地图 - SiteMap</h1>
    </div>
    <div class="card shadow mb-4 mt-2">
        <div class="card-body">
            <form action="plugin.php?plugin=sitemap&action=setting" method="post">
                <div>
                    <p>sitemap文件名：
                        <input size="12" name="sitemap_name" type="text" value="<?php echo $sitemap_name; ?>"/></p>
                    <p>在网站底部显示：
                        <input size="12" name="sitemap_show_footer" type="checkbox" value="1" <?php echo $ex1; ?>/></p>
                    <p>最新评论时间作为最后更新时间：
                        <input size="12" name="sitemap_comment_time" type="checkbox" value="1" <?php echo $ex2; ?>/></p>
                    <p>更新周期可选值：always（经常更新）, hourly（每小时）, daily（每天）, weekly（每周）, monthly（每月）, yearly（每年）, never（不更新）</p>
                    <table class="table table-bordered table-striped table-hover dataTable no-footer">
                        <tbody>
                        <tr>
                            <td></td>
                            <td>日志</td>
                            <td>页面</td>
                            <td>分类</td>
                            <td>标签</td>
                            <td>归档</td>
                        </tr>
                        <tr>
                            <td>更新周期</td>
                            <?php foreach ($sitemap_changefreq as $value): ?>
                                <td><input size="5" name="sitemap_changefreq[]" type="text" value="<?php echo $value; ?>"/></td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td>权重</td>
                            <?php foreach ($sitemap_priority as $value): ?>
                                <td><input size="5" name="sitemap_priority[]" type="text" value="<?php echo $value; ?>"/></td>
                            <?php endforeach; ?>
                        </tr>
                        </tbody>
                    </table>
                    <p><input type="submit" value="保 存" class="btn btn-success btn-sm"/></p>
                </div>
            </form>
            <div class=line></div>
            <form action="plugin.php?plugin=sitemap&action=setting" method="post">
                <input type="hidden" name="update" value="1"/>
                <input type="submit" value="更新sitemap" class="btn btn-success btn-sm"/>
            </form>
            <hr>
            <p>注：关于什么是sitemap，<a href="https://ziyuan.baidu.com/wiki/44" target="_blank">参考资料</a></p>
            <p>sitemap地址：<span class="text-success"><?= $sitemap_url ?></span></p>
            <p>提交sitemap入口：
                <a href="https://ziyuan.baidu.com/linksubmit" target="_blank">百度</a>，
                <a href="https://search.google.com/search-console?hl=zh-cn" target="_blank">Google</a>，
                <a href="https://zhanzhang.sogou.com/#" target="_blank">搜狗搜索</a>，
                <a href="https://www.bing.com/webmasters/about" target="_blank">Bing</a>，
                <a href="https://zhanzhang.so.com/" target="_blank">360搜索</a>
            </p>
        </div>
    </div>

    <script>
        setTimeout(hideActived, 3600);
        $("#menu_category_ext").addClass('active');
    </script>
    <?php
}

function plugin_setting() {
    extract(sitemap_config());
    if (!isset($_POST['update'])) {
        $changefreq2 = isset($_POST['sitemap_changefreq']) ? $_POST['sitemap_changefreq'] : array();
        $priority2 = isset($_POST['sitemap_priority']) ? $_POST['sitemap_priority'] : array();
        $sitemap_name2 = isset($_POST['sitemap_name']) ? strval($_POST['sitemap_name']) : '';
        foreach ($changefreq2 as $key => $value) {
            if (!in_array($value, array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'))) {
                $sitemap_changefreq[$key] = 'daily';
            } else {
                $sitemap_changefreq[$key] = $value;
            }
        }
        foreach ($priority2 as $key => $value) {
            if ((float)$value > 1.0 || (float)$value <= 0) {
                $sitemap_priority[$key] = '0.8';
            } else {
                $sitemap_priority[$key] = $value;
            }
        }
        if ($sitemap_name2 != '' && $sitemap_name != $sitemap_name2) {
            if (!@rename(EMLOG_ROOT . '/' . $sitemap_name, EMLOG_ROOT . '/' . $sitemap_name2)) {
                emMsg("重命名文件{$sitemap_name}失败,请设置根目录下{$sitemap_name}权限为777（LINUX)/everyone可写（windows）", './plugin.php?plugin=sitemap');
            }
            $sitemap_name = $sitemap_name2;
        }
        $sitemap_show_footer = isset($_POST['sitemap_show_footer']) ? addslashes($_POST['sitemap_show_footer']) : 0;
        $sitemap_comment_time = isset($_POST['sitemap_comment_time']) ? addslashes($_POST['sitemap_comment_time']) : 0;

        $conf_data = compact('sitemap_name', 'sitemap_changefreq', 'sitemap_priority', 'sitemap_show_footer', 'sitemap_comment_time');
        $plugin_storage = Storage::getInstance('sitemap');
        $plugin_storage->setValue('conf_data', $conf_data, 'array');
    }
    if (!sitemap_update()) {
        emMsg("更新sitemap失败,请设置根目录下{$sitemap_name}权限为777（LINUX）/everyone可写（windows）", './plugin.php?plugin=sitemap');
    }
    return true;
}