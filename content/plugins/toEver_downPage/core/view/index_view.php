<?php if($options['switch_main'] == 1 && !empty($downData) && !empty(json_decode($downData['list'], true)) && $downData['state'] === 'y'):?>
<div id="teDown_box">
    <style type="text/css">
    <?php if(!empty($options['button_color'])):?>
        #teDown_box .teDown_box_title{border-color: <?=$options['button_color']?>;}
        #teDown_box .teDown_box_main .teDown_box_main_type0>a{background-color: <?=$options['button_color']?>;border-radius: <?=$options['button_border_radius']?>px;}
    <?php endif;?>
    <?php if(!empty($options['button_text_color'])):?>
        #teDown_box .teDown_box_main .teDown_box_main_type0>a{color: <?=$options['button_text_color']?>;}
        #teDown_box .teDown_box_main .teDown_box_main_type0>a:hover{color: <?=$options['button_text_color']?>!important;}
    <?php endif;?>
    <?php if($options['switch_button_icon'] != 1):?>
        #teDown_box .teDown_box_main .teDown_box_main_type0>a>svg.icon{display: none;}
        #teDown_box .teDown_box_main .teDown_box_main_type0>a>div{align-items: center;}
    <?php endif;?>
    <?php if(!empty($options['button_text_color'])):?>
        #teDown_box .teDown_box_main .teDown_box_main_type0>a>svg.icon path{fill: <?=$options['button_text_color']?>;}
    <?php endif;?>
    <?php if(!empty($options['reply_border_color'])):?>
        #teDown_box .teDown_box_main .teDown_box_main_type1{border-color: <?=$options['reply_border_color']?>;}
        #teDown_box .teDown_box_main .teDown_box_main_type1>span{color: <?=$options['reply_border_color']?>;}
    <?php endif;?>
    </style>
<?php if($options['switch_tips_title'] == 1):?>
    <div class="teDown_box_title"><?=!empty($options['tips_title_text']) ? $options['tips_title_text'] : '附件下载'?></div>
<?php endif;?>
    <div class="teDown_box_main">
<?php if($lock):?>
    <?php switch($downData['type']): case 1:?>
            <div class="teDown_box_main_type1 teDown_box_marginTop20">
                下载链接已隐藏，请<span>回复</span>后刷新页面即可下载。
            </div>
        <?php break;?>
        <?php case 2:?>
            <div class="teDown_box_main_type2 teDown_box_marginTop20">
                下载链接已隐藏，请<span>登录</span>后刷新页面即可下载。
            </div>
        <?php break;?>
        <?php case 3:?>
            <div class="teDown_box_main_type3 teDown_box_marginTop20">
                <p>下载链接已隐藏，请<span>付费</span>后刷新页面即可下载。</p>
                <p class="pay-button">
                    <a href="javascript:;" onclick="payModal('<?= $logData['logid']; ?>', '付费下载', '<?= $logData['log_title']; ?>', '<?= $downData['money']; ?>')">支付￥<?= $downData['money']; ?></a>
                    <a href="javascript:;" onclick="payRestore('<?= $logData['logid']; ?>')" data-tips="（已购买？点此恢复）">恢复订单</a>
                </p>
            </div>
        <?php break;?>
    <?php endswitch;?>
<?php else:?>
    <div class="teDown_box_main_type0">
    <?php foreach(json_decode($downData['list'], true) as $key => $val):?>
        <a href="javascript:;" onclick="window.open('<?=$val['url']?>')">
            <svg t="1692865061963" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="15187" width="24" height="24"><path d="M761.98 413.12c0.25-4.4 0.39-8.82 0.39-13.28 0-127.18-102.84-230.28-229.71-230.28s-229.71 103.1-229.71 230.28c0 0.67 0.02 1.33 0.03 2a213.156 213.156 0 0 0-38.91-3.58c-117.2 0-212.21 95.25-212.21 212.74 0 117.49 95.01 212.74 212.21 212.74 2.94 0 5.86-0.08 8.77-0.2 2.54 0.13 5.09 0.2 7.66 0.2h467.35c2.82 0 5.61-0.09 8.39-0.24 108.96-5.16 195.72-95.13 195.72-205.36 0.01-108.3-83.73-197.04-189.98-205.02zM616.33 584.24l-90.86 93.93c-0.78 1.11-1.66 2.17-2.63 3.17-3.95 4.09-8.9 6.62-14.09 7.61-8.34 1.77-17.38-0.51-23.97-6.89a25.975 25.975 0 0 1-3.16-3.68l-93.5-90.45c-10.53-10.19-10.81-26.99-0.62-37.52 10.19-10.53 26.99-10.81 37.52-0.62l45.09 43.62c0-0.06-0.01-0.12-0.01-0.18l-2.43-146.62c-0.3-17.83 13.92-32.52 31.75-32.82 17.83-0.3 32.52 13.92 32.82 31.75l2.43 146.63v0.17l43.52-44.99c10.19-10.53 26.99-10.81 37.52-0.62 10.53 10.17 10.81 26.97 0.62 37.51z" fill="#ffffff" p-id="15188"></path></svg>
            <div><span><?=$val['source']?></span><?php if($options['radio_displayType'] != 2 && !empty($val['password'])):?><item>密码：<?=$val['password']?></item><?php endif;?></div>
        </a>
    <?php endforeach;?>
    </div>
<?php endif;?>
    </div>
</div>
<?php endif;?>