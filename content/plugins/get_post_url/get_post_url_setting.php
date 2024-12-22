<?php

!defined('EMLOG_ROOT') && exit('access deined!');
function plugin_setting_view()
{
    $post_count = isset($_POST['post-count']) ? intval($_POST['post-count']) : 10;
    ?>
    <h3>文章URL列表</h3><br/>
    <form method="post" class="form-group">
        <label for="post-count">要提取的文章数（您随意，新版本暂时还没有出现卡死的现象）：</label>
        <div class="input-group">
            <input class="form-control" type="number" id="post-count" value="<?php echo $post_count ?: 10; ?>"
                   name="post-count" min="1" max="9223372036854775807" required>
            <button type="submit" class="btn btn-primary btn-sm">获取</button>
        </div>
    </form>
    <?php
    if (empty($post_count) || !is_numeric($post_count)) {
        $post_count = 10;
    }
    $urls = [];
    $db = Database::getInstance();
    $sql = "SELECT * FROM " . DB_PREFIX . "blog WHERE type='blog' ORDER BY date DESC LIMIT 0,{$post_count}";
    $res = $db->query($sql);
    while ($row = $db->fetch_array($res)) {
        $urls[] = Url::log($row['gid']);
    }
    if ($urls) {
        $arrayCount = count($urls);
        $arrayString = implode("\n", $urls);
        echo '<textarea class="form-control" readonly rows="' . $arrayCount . '">' . $arrayString . '</textarea>';
    }
    ?>
    <script>
        $("#menu_category_ext").addClass('active');
        $("#menu_ext").addClass('show');
        $("#gpu_ame").addClass('active');
    </script>
    <?php
}