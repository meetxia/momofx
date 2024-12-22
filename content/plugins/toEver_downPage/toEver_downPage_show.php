<?php
!defined('EMLOG_ROOT') && exit('access deined!');

//准备工作
$plugin_name = trim(basename(__DIR__), '/'); 

//定义系统配置
$em_option = Option::getAll();

//监测是否激活此插件
if(strpos($em_option['active_plugins'], $plugin_name) === false){
	emMsg('插件未激活，请激活');
}

//获取插件配置
global $options;
$storage = Storage::getInstance('toEver_downPage');
$options = unserialize($storage->getValue('options'));

//判断模式是否开启
if ($options['radio_displayType'] != 2) {
    emMsg('未开启单页下载模式');
}

//加载db类
global $xfdb;

//取参数
$gid = Input::getIntVar('gid');

if ($gid) {
    //获取当前文章下的下载列表的数量
    $downDataCount = $xfdb->count("toEverDown_list", "*", [
        'gid' => $gid,
        'state' => 'y'
    ]);
    if (!$downDataCount) {
        emMsg('当前文章未开启下载。');
    }
} else {
    emMsg('参数有误，无法访问，请检查问题。');
}

//取当前文章信息
$log = $xfdb->get("blog", "*", [
    'type' => 'blog',
    'hide' => 'n',
    'checked' => 'y',
    'gid' => $gid
]);

//取下载列表信息
$downData = $xfdb->get("toEverDown_list", "*", [
    'gid' => $gid,
    'state' => 'y'
]);

//内容规则
$lock = true;
switch ($downData['type']) {
    case 0:
        $lock = false;
    break;
    case 1:
        if (!empty(UID) && ROLE === ROLE_ADMIN) {
            $lock = false;
        } elseif (isset($_COOKIE['toEverDownPage_comment_gid']) && $_COOKIE['toEverDownPage_comment_gid'] == $gid) {
            $lock = false;
        }
    break;
    case 2:
        if (!empty(UID)){
            $lock = false;
        }
    break;
    case 3:
        if (!empty(UID) && ROLE === ROLE_ADMIN) {
            $lock = false;
        } elseif (isset($_COOKIE['pay_num'])){
            $row = $xfdb->get('toEverDown_order', [
                'state'
            ], [
                'post_id' => $gid,
                'pay_num' => $_COOKIE['pay_num']
            ]);
            if(!empty($row) && $row['state'] == 1){
                $lock = false;
            }
        }
    break;
}

/* 去除字符串中的空格及换行符 */
function htmlConvertCompress($string = '', $backslash = true){
	if(!empty($string)){
		$string = str_replace(["\n", "\r", "\t", "\s"], '', $string);
		if($backslash){
			$string = addslashes($string);
		}
	}
	return $string;
}

/* 广告输出函数 */
function advertisement($num = 1){
    global $options;
    $html = '';
    if(!empty($options["ad_text_$num"])){
        $html .= '<div class="jump jump-text">'.$options["ad_text_$num"].'</div>';
    }
    if(!empty($options["ad_pic_$num"])){
        $html .= '<div class="jump jump-pic">'.$options["ad_pic_$num"].'</div>';
    }
    if(!empty($options["ad_pic_bisection_$num"])){
        $html .= '<div class="jump jump-pic jump-bisection">'.$options["ad_pic_bisection_$num"].'</div>';
    }
    if(!empty($options["ad_pic_block_$num"])){
        $html .= '<div class="jump jump-pic jump-block">'.$options["ad_pic_block_$num"].'</div>';
    }
    if(!empty($html)){
        $html = '<script type="text/javascript">document.write(\''.htmlConvertCompress($html).'\');</script>';
    }
    return $html;
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= $log['title']; ?> - 下载页 - <?= $em_option['site_title']; ?></title>
    <meta name="keywords" content="<?= $em_option['site_key']; ?>">
    <meta name="description" content="<?= $em_option['site_description']; ?>">
    <meta http-equiv="Cache-Control" content="no-transform" />
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
    <link type="text/css" rel="stylesheet" href="<?= TOEVER_DOWNPAGE_URL; ?>static/layui/css/layui.css?v=<?= TOEVER_DOWNPAGE_VERSION; ?>">
    <link type="text/css" rel="stylesheet" href="<?= TOEVER_DOWNPAGE_URL; ?>static/index/page-main.css?v=<?= TOEVER_DOWNPAGE_VERSION; ?>">
    <script type="text/javascript" src="<?= TOEVER_DOWNPAGE_URL; ?>static/layui/layui.js?v=<?= TOEVER_DOWNPAGE_VERSION; ?>"></script>
    <style type="text/css">
<?php if(empty(toEverDownPage::getInstance()->getCover($log))): ?>
        main{grid-template-rows: 150px 1fr;}
        main .item.title{grid-column-start: 1;grid-column-end: 3;}
<?php endif;?>
<?php if(!empty($options['button_color'])): ?>
        main .item.downlist .type0>a{background-color: <?= $options['button_color']; ?>;border-radius: <?= $options['button_border_radius']; ?>px;}
<?php endif; if(!empty($options['button_text_color'])): ?>
        main .item.downlist .type0>a{color: <?= $options['button_text_color']; ?>;}
        main .item.downlist .type0>a:hover{color: <?= $options['button_text_color']; ?>!important;}
<?php endif; if($options['switch_button_icon'] != 1): ?>
        main .item.downlist .type0>a>svg.icon{display: none;}
        main .item.downlist .type0>a>div{align-items: center;}
<?php endif; if(!empty($options['button_text_color'])): ?>
        main .item.downlist .type0>a>svg.icon path{fill: <?= $options['button_text_color']; ?>;}
<?php endif; if(!empty($options['reply_border_color'])): ?>
        main .item.downlist .type1,
        main .item.downlist .type2{border-color: <?= $options['reply_border_color']; ?>;}
        main .item.downlist .type1>span,
        main .item.downlist .type2>span{color: <?= $options['reply_border_color']; ?>;}
<?php endif; if(!empty($options['page_background_color_1']) && !empty($options['page_background_color_2'])): ?>
        body:before,
        main::before{background-image: linear-gradient(0deg, <?= $options['page_background_color_1']; ?> 10%, <?= $options['page_background_color_2']; ?> 100%);}
<?php endif;?>
    </style>
</head>
<body class="noTransition">
<main>
    <div class="item title">
        <h1><?= $log['title'] ;?></h1>
        <p><?php
            if (!empty($log['excerpt'])) {
                echo extractHtmlData($log['excerpt'], 100);
            } elseif(!empty($log['content'])) {
                echo extractHtmlData($log['content'], 100);
            } else {
                echo '此文无摘要。';
            }
        ?></p>
    </div>
    <div class="item cover">
        <?php if(!empty(toEverDownPage::getInstance()->getCover($log))): ?><img src="<?= toEverDownPage::getInstance()->getCover($log); ?>" alt="<?= $log['title'] ;?>"><?php endif; ?>
    </div>
    <div class="item downlist">
        <?= advertisement(); ?>
<?php if($lock): switch($downData['type']): case "1": ?>
        <div class="type1">
            下载链接已隐藏，请<span onclick="window.location.href='<?= Url::log($gid); ?>'">回复</span>后刷新页面即可下载。
        </div>
    <?php break; case "2": ?>
        <div class="type2">
            下载链接已隐藏，请<span onclick="window.open('/admin')">登录</span>后刷新页面即可下载。
        </div>
    <?php break; case "3": ?>
        <div class="type3">
            <p>下载链接已隐藏，请<span>付费</span>后刷新页面即可下载。</p>
            <p class="pay-button">
                <a href="javascript:;" onclick="payModal('<?= $gid; ?>', '付费下载 - 支付宝', '<?= $log['title']; ?>', '<?= $downData['money']; ?>')">支付￥<?= $downData['money']; ?></a>
                <a href="javascript:;" onclick="payRestore('<?= $gid; ?>')" data-tips="（已购买？点此恢复）">恢复订单</a>
            </p>
        </div>
    <?php break; ?>
<?php endswitch; else: ?>
        <div class="type0">
<?php foreach(json_decode($downData['list'], true) as $key => $val): ?>
    <a href="javascript:;" onclick="window.open('<?= $val['url']; ?>');">
        <svg t="1692865061963" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="15187" width="24" height="24"><path d="M761.98 413.12c0.25-4.4 0.39-8.82 0.39-13.28 0-127.18-102.84-230.28-229.71-230.28s-229.71 103.1-229.71 230.28c0 0.67 0.02 1.33 0.03 2a213.156 213.156 0 0 0-38.91-3.58c-117.2 0-212.21 95.25-212.21 212.74 0 117.49 95.01 212.74 212.21 212.74 2.94 0 5.86-0.08 8.77-0.2 2.54 0.13 5.09 0.2 7.66 0.2h467.35c2.82 0 5.61-0.09 8.39-0.24 108.96-5.16 195.72-95.13 195.72-205.36 0.01-108.3-83.73-197.04-189.98-205.02zM616.33 584.24l-90.86 93.93c-0.78 1.11-1.66 2.17-2.63 3.17-3.95 4.09-8.9 6.62-14.09 7.61-8.34 1.77-17.38-0.51-23.97-6.89a25.975 25.975 0 0 1-3.16-3.68l-93.5-90.45c-10.53-10.19-10.81-26.99-0.62-37.52 10.19-10.53 26.99-10.81 37.52-0.62l45.09 43.62c0-0.06-0.01-0.12-0.01-0.18l-2.43-146.62c-0.3-17.83 13.92-32.52 31.75-32.82 17.83-0.3 32.52 13.92 32.82 31.75l2.43 146.63v0.17l43.52-44.99c10.19-10.53 26.99-10.81 37.52-0.62 10.53 10.17 10.81 26.97 0.62 37.51z" fill="#ffffff" p-id="15188"></path></svg>
        <div><span><?= $val['source']; ?></span><?php if(!empty($val['password'])): ?><item>密码：<?= $val['password']; ?></item><?php endif; ?></div>
    </a>
<?php endforeach; ?>
        </div>
<?php endif; ?>
        <div class="panel">
            <div class="panel-heading">
                <h3>下载说明</h3>
            </div>
            <div class="panel-body">
                <?php if(!empty($options['download_des'])): echo $options['download_des']; else:?>
                <ul class="help">
                    <li>1、本站提供的压缩包若无特别说明，解压密码均为<em><?= $_SERVER['HTTP_HOST']; ?></em>。</li>
                    <li>2、下载后文件若为压缩包格式，请安装7Z软件或者其它压缩软件进行解压。</li>
                    <li>3、文件比较大的时候，建议使用下载工具进行下载，浏览器下载有时候会自动中断，导致下载错误。</li>
                    <li>4、资源可能会由于内容问题被和谐，导致下载链接不可用，遇到此问题，请到文章页面进行反馈，我们会及时进行更新的。</li>
                    <li>5、其他下载问题请自行搜索教程，这里不一一讲解。</li>
                </ul>
                <?php endif; ?>
            </div>
        </div>
        <div class="panel">
            <div class="panel-heading">
                <h3>站长声明</h3>
            </div>
            <div class="panel-body">
                <?php if(!empty($options['station_master_des'])): echo $options['station_master_des']; else:?>
                <span>本站大部分下载资源收集于网络，只做学习和交流使用，版权归原作者所有，若为付费资源，请在下载后24小时之内自觉删除，若作商业用途，请到原网站购买，由于未及时购买和付费发生的侵权行为，与本站无关。本站发布的内容若侵犯到您的权益，请联系本站删除，我们将及时处理！</span>
                <?php endif; ?>
            </div>
        </div>
        <?= advertisement(2); ?>
    </div>
</main>
<footer>
    Copyright © <?= date('Y'); ?> <a href="<?= BLOG_URL; ?>" title="<?= $em_option['blogname']; ?>" target="_blank"><?= $em_option['blogname']; ?></a>  Theme by <a href="http://woaif.cn" target="_blank">小风</a>
</footer>
<script type="text/javascript" src="<?= TOEVER_DOWNPAGE_URL; ?>static/index/page-main.js?v=<?= TOEVER_DOWNPAGE_VERSION; ?>"></script>
</body>
</html>