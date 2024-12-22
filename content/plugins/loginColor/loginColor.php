<?php
/*
Plugin Name: 登录背景图
Version: 1.1.4
Plugin URL:https://www.emlog.net/plugin/detail/634
Description: 自定义登录页面的背景图
Author: emlog
Author URL: https://www.emlog.net/author/index/577
*/

defined('EMLOG_ROOT') || exit('access denied!');

function set_bg() {
    $plugin_storage = Storage::getInstance('loginColor');
    $cur_gb_img = $plugin_storage->getValue('cur_gb_img');
    $my_bg_img = $plugin_storage->getValue('my_bg_img');
    $is_rand = $plugin_storage->getValue('is_rand');
    $bing_img = $plugin_storage->getValue('bing_img');

    if ($is_rand && !empty($my_bg_img)) {
        $randomIndex = array_rand($my_bg_img);
        $cur_gb_img = $my_bg_img[$randomIndex];
    }

    if ($bing_img) {
        $cur_gb_img = 'https://www.bing.com/th?id=OHR.PrasatPhanom_EN-US7990643175_1920x1080.jpg&rf=LaDigue_1920x1080.jpg&pid=hp';
    }
    ?>
    <style>
        body {
            background-image: url('<?=$cur_gb_img?>');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.6);
        }
    </style>
    <script>
        $(document).ready(function () {
            $('body').removeClass();
        });
    </script>
<?php }

addAction('login_head', 'set_bg');
