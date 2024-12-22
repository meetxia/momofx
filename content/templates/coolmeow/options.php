<?php
/*@support tpl_options*/
!defined('EMLOG_ROOT') && exit('access deined!');

$options = array(
    'TplOptionsNavi' => array(
        'type' => 'radio',
        'name' => '定义设置项标签页名称',
        'description'=>'
<div id="optionsframework">

<div id="options-group-14" class="group " style=""><h3><span class="navbar-toggler-icon">
              <span class="navbar-toggler-bar bar1"></span>
              <span class="navbar-toggler-bar bar2"></span>
              <span class="navbar-toggler-bar bar3"></span>
            </span>关于主题 - 二次元博客主题</h3>
<div id="banquan" class="section section-info">
<h4 class="heading">★ 开源协议</h4>

		 <p>1.主题依托于开源协议 GPL V3.0，如果不接受本协议，请立即删除</p>
		 <p>2.请遵循开源协议，保留主题底部版权信息，如果不接受本协议，请立即删除；</p>		
		
</div>
<div id="shiyong" class="section section-info">
<h4 class="heading">★ 使用协议/注意事项</h4>

		 <p>1.主题仅供博客爱好者合法建站交流！禁止使用于违法用途！如如主题使用者不能遵守此规定，请立即删除；</p>
		 <p>2.严禁利用本主题严重侵犯他人隐私权，如主题使用者不能遵守此规定，请立即删除；</p>
		 <p>3.使用主题请遵守网站服务器当地相关法律和站长当地相关法律，如不能遵守请立即删除；</p>
		 <p>4.主题不支持任何作为非法违规用途站点！如不能遵守请立即删除；</p>
		 <p>5.主题开源无任何加密文件，对于因用户使用本主题而造成自身或他人隐私泄露，等任何不良后果，均由用户自行承担，主题作者不负任何责任；</p>
		 <p>6.本主题共享下载，如果用户自行下载使用，即表明用户自愿并接受本协议所有条款。 如果用户不接受本协议，请立即删除；</p>
		
</div>
<div id="banquan" class="section section-info">
<h4 class="heading">★ 主题信息</h4>

		 <p>当前版本：2.7.5</p>
		 <p>查看主题：<a href="https://www.emlog.net/template/detail/1033" target="_blank" rel="external nofollow" class="url themes-inf">更新日志</a></p>		
		 <p>主题QQ群：<a href="http://qm.qq.com/cgi-bin/qm/qr?_wv=1027&k=HJ4mtRzqTp57yg1cn57h_pTSTvgVKC7G&authKey=ubYnaLgBGSDBdEOO7rgGiwJduM645P%2FXs6irax2R3F5DNE%2Bv0aYpofdLJLf5EadS&noverify=0&group_code=683516998" target="_blank" rel="external nofollow" class="url themes-inf">683516998</a></p>
		
</div>
</div>
</div>
<style>
#optionsframework h3 {
    cursor: default;
    background: rgba(125, 34, 226, 0.1);
    font-size: 14px;
    line-height: 1.4;
    color: #7d22e2;
    margin: 0;
    padding: 13px 12px;
    font-weight: bold;
}
#optionsframework .section .heading {
    font-size: 15px;
    font-weight: 400;
    line-height: 1;
    display: inline-block;
    position: absolute;
    left: 10px;
    top: -8px;
    padding: 0 10px;
    color: #333;
    background-color: #f7f8f9;
}
p {
    font-size: 13px;
    line-height: 1.5;
    margin: 1em 0;
}
#optionsframework .section {
    margin: 25px 30px;
    border: 1px rgb(125 34 226 / 30%) solid;
    padding: 20px;
    position: relative;
    margin-bottom: 20px;
    border-radius: 5px;
}
</style>',
        'values' => array(
            'tpl-home' => '全局设置',
            'tpl-footer' => '底部设置',
            'tpl-side' => '侧边设置',
            'tpl-log' => 'Banner设置',
            'tpl-article' => '文章设置',
            'tpl-comment' => '评论设置',
            'tpl-socialize' => '社交设置',
            'tpl-web' => '前端加速',
            'tpl-music' => '音乐设置',
        ),
    ),
    'logo' =>array(
        'labels' =>'tpl-home',
        'type' => 'image',
        'name' => '站点logo设置',
        'values' => array(
            TEMPLATE_URL . 'assets/images/logo.png',
        ),
        'description' => '点击上传站点logo。'
    ),
    'favicon_src' =>array(
        'labels' =>'tpl-home',
        'type' => 'image',
        'name' => 'favicon地址',
        'values' => array(

            BLOG_URL . 'favicon.ico',
        ),
        'description' => '点击上传站点favicon。'
    ),
    'sidebar_on' => array(
        'labels' => 'tpl-home',
        'type' => 'radio',
        'name' => '全站布局(侧栏/无侧栏切换)[模块边框在文章设置里设置]',
        'values' => array(
            'col-1' => '无侧栏',
            'col-2' => '有侧栏',
        ),
        'default' => 'col-2',
    ),
    'blog_border' => array(
        'labels' => 'tpl-home',
        'type' => 'radio',
        'name' => '文章列表边框/全站模块边框',
        'values' => array(
            'border1' => '线条边框',
            'border2' => '阴影模块',
        ),
        'default' => 'border1',
    ),
    'body_grey' => array(
        'labels' => 'tpl-home',
        'type' => 'checkon',
        'name' => '悼念模式',
        'values' => array('1'=>'开启'),
        'default' => '0',
    ),
    'body_color' => array(
        'labels' => 'tpl-home',
        'type' => 'checkon',
        'name' => '开启网页彩色背景色',
        'values' => array('1'=>'开启'),
        'default' => '0',
    ),
    'sakura' => array(
        'labels' => 'tpl-home',
        'type' => 'checkon',
        'name' => '开启网页樱花飘落',
        'values' => array('1'=>'开启'),
        'default' => '0',
    ),
    'lantern' => array(
        'labels' => 'tpl-home',
        'type' => 'checkon',
        'name' => '开启节日红灯笼',
        'values' => array('1'=>'开启'),
        'default' => '0',
    ),
    'lanternfont1' => array(
        'labels' =>'tpl-home',
        'type' =>'text',
        'name' =>'灯笼文字1',
        'values' => array(''),
        'default' => '新',
        'description' =>'',
    ),
    'lanternfont2' => array(
        'labels' =>'tpl-home',
        'type' =>'text',
        'name' =>'灯笼文字2',
        'values' => array(''),
        'default' => '春',
        'description' =>'',
    ),
    'lanternfont3' => array(
        'labels' =>'tpl-home',
        'type' =>'text',
        'name' =>'灯笼文字3',
        'values' => array(''),
        'default' => '快',
        'description' =>'',
    ),
    'lanternfont4' => array(
        'labels' =>'tpl-home',
        'type' =>'text',
        'name' =>'灯笼文字4',
        'values' => array(''),
        'default' => '乐',
        'description' =>'',
    ),
    'blog_new_style' => array(
        'labels' => 'tpl-home',
        'type' => 'radio',
        'new'          =>  'NEW',
        'name' => '文章列表最新文章标识',
        'values' => array(
            '1' => '开启',
            '0' => '关闭',
        ),
        'default' => '0',
        'description' => ''
    ),
    'blog_page_link' => array(
        'labels' => 'tpl-home',
        'type' => 'radio',
        'new'          =>  'NEW',
        'name' => '文章跳转&页面跳转方式',
        'values' => array(
            '1' => '外链直跳',
            '0' => '外链提醒',
        ),
        'default' => '0',
        'description' => ''
    ),
    'nav_ico' => array(
        'labels' =>'tpl-home',
        'type' =>'text',
        'name' =>'导航图标设置',
        'values' => array('fa-home|fa-heart-o|fa-file-image-o|fa-user-o|fa-user-o'),
        'multi' => true,
        'description' =>'按照上方例子设置导航图标，按顺序设置，每个中间用|隔开，图标查看网址<a href="http://www.fontawesome.com.cn/faicons/" target="_blank" rel="nofollow">http://www.fontawesome.com.cn/faicons/</a>',
    ),
    'sousuo' => array(
        'labels' => 'tpl-home',
        'type' => 'checkon',
        'name' => '导航搜索功能开启',
        'values' => array('1'=>'开启'),
        'default' => '1',
    ),
    'lolijump' => array(
        'labels' => 'tpl-home',
        'type' => 'checkon',
        'name' => '网页右侧看板开关，附带点击回到顶部功能',
        'values' => array('1'=>'开启'),
        'default' => '0',
    ),
    'lolijumpsister' => array(
        'labels' => 'tpl-home',
        'type' => 'radio',
        'name' => '选择前端看板形象',
        'values' => array(
            'lolisister1' => '看板萝莉-姐姐',
            'lolisister2' => '看板萝莉-妹妹',
            'dance' => '看板娘-舞娘娘',
            'meow' => '看板娘-喵小娘',
            'lemon' => '看板妹-柠檬妹',
            'bear' => '看板熊-熊宝宝',
        ),
        'default' => 'lolisister1',
    ),
    'hitokoto_on' => array(
        'labels' => 'tpl-home',
        'type' => 'checkon',
        'name' => '首页一言开关',
        'values' => array('1'=>'开启'),
        'default' => '1',
    ),
    'hitokoto_text' => array(
        'labels' => 'tpl-home',
        'type' => 'radio',
        'name' => '首页一言句子分类',
        'values' => array(
            'b' => '漫画',
            'c' => '游戏',
            'd' => '文学',
            'e' => '原创',
            'f' => '来自网络',
            'g' => '其他',
            'h' => '影视',
            'i' => '诗词',
            'j' => '网易云',
            'k' => '哲学',
        ),
        'default' => 'b',
    ),
    'runtime' => array(
        'labels' => 'tpl-footer',
        'type' => 'checkon',
        'name' => '底部显示页面执行时间',
        'values' => array('1'=>'开启'),
        'default' => '0',
    ),
    'footer_seo' => array(
        'labels' =>'tpl-footer',
        'type' =>'text',
        'name' =>'网站底部导航链接',
        'values' => array('<li class="nav-item"><a href="'.BLOG_URL.('/sitemap.xml').'" target="_blank" class="nav-link">网站地图</a></li>'),
        'multi' => true,
        'description' =>'',
    ),
    'footer_info' => array(
        'labels' =>'tpl-footer',
        'type' =>'text',
        'name' =>'网站底部自定义信息（如备案号支持HTML代码）',
        'values' => array(''),
        'multi' => true,
        'description' =>'',
    ),
    'trackcode' => array(
        'labels' =>'tpl-footer',
        'type' =>'text',
        'name' =>'统计代码（底部第三方流量数据统计代码）',
        'values' => array(''),
        'multi' => true,
        'description' =>'',
    ),
    'diy_code_footer' => array(
        'labels' =>'tpl-footer',
        'type' =>'text',
        'name' =>'自定义代码（适用于自定义如css js代码置于底部加载）',
        'values' => array(''),
        'multi' => true,
        'description' =>'',
    ),
    'blog_run_time' => array(
        'labels' => 'tpl-footer',
        'type' => 'checkon',
        'name' => '网站运行时间',
        'values' => array('1'=>'开启'),
        'default' => '0',
    ),
    'blog_reg_time' => array(
        'labels' => 'tpl-footer',
        'type' => 'text',
        'name' => '网站运行时间',
        'values' => array('1/1/2024'),
        'default' => '1/1/2024',
    ),
    'banner_height' => array(
        'labels' =>'tpl-log',
        'type' =>'text',
        'name' =>'自定义【PC端】Banner高度（默认580高度,留空则默认）',
        'values' => array(''),
        'description' =>'',
    ),
    'm_banner_height' => array(
        'labels' =>'tpl-log',
        'type' =>'text',
        'name' =>'自定义【移动端】Banner高度（默认580高度,留空则默认）',
        'values' => array(''),
        'description' =>'',
    ),
    'banner_default' =>array(
        'labels' =>'tpl-log',
        'type' => 'image',
        'name' => '固定Banner背景图',
        'values' => array(
            TEMPLATE_URL . 'assets/images/banner/1.jpg',
        ),
        'description' => '固定Banner背景图。'
    ),
    'banner_rand' => array(
        'labels' => 'tpl-log',
        'type' => 'checkon',
        'name' => '（随机）Banner背景图（开启后上方↑图片失效，在主题/assets/images/banner/下加入图片并命名即可）',
        'values' => array('1'=>'开启'),
        'default' => '1',
        'description' => '（开启后上方↑图片失效，在主题/assets/images/banner/下加入图片并命名即可）'
    ),
    'banner_rand_n' => array(
        'labels' =>'tpl-log',
        'type' =>'text',
        'name' =>'Banner随机图片数量',
        'values' => array(''),
        'default' => '1',
        'description' =>'图片命名为1.jpg 2.jpg...x.jpg ，x=1-你上面设置的数量，格式JPG）',
    ),
    'banner_api_on' => array(
        'labels' => 'tpl-log',
        'type' => 'checkon',
        'name' => '使用外链APi-Banner图片',
        'values' => array('1'=>'开启'),
        'default' => '0',
        'description' =>'（开启后上方图片功能全失效）',
    ),
    'banner_api_url' => array(
        'labels' =>'tpl-log',
        'type' =>'text',
        'name' =>'外链APi-Banner图片',
        'values' => array(''),
        'description' =>'',
    ),
    'wow_on' => array(
        'labels' => 'tpl-article',
        'type' => 'checkon',
        'values' => array('1'=>'开启'),
        'name' => '文章相关模块渐进动态效果',
        'default' => '0',
    ),
    'thumbnail_rand_n' => array(
        'labels' => 'tpl-article',
        'type' =>'text',
        'name' =>'文章随机缩略图数量（/assets/images/rand/N.jpg，N=1-你设置的数量）',
        'values' => array('5'),
        'description' =>'',
    ),
    'thumbnail_api' => array(
        'labels' => 'tpl-article',
        'type' => 'checkon',
        'name' => '开启API随机缩略图',
        'values' => array('1'=>'开启'),
        'default' => '0',
    ),
    'thumbnail_api_url' => array(
        'labels' => 'tpl-article',
        'type' =>'text',
        'name' =>'自定义api随机缩图URL',
        'values' => array(''),
        'description' =>'',
    ),
    'link—open' => array(
        'labels' => 'tpl-article',
        'type' => 'radio',
        'name' => '链接打开方式',
        'values' => array(
            '0' => '原窗口',
            '1' => '新窗口',
        ),
        'default' => '0',
    ),
    'post_related_s' => array(
        'labels' => 'tpl-article',
        'type' => 'checkon',
        'name' => '正文底部相关文章',
        'values' => array('1'=>'开启'),
        'default' => '0',
    ),
    'post_related_n' => array(
        'labels' => 'tpl-article',
        'type' =>'text',
        'name' =>'相关文章-显示文章数量',
        'values' => array('3'),
        'description' =>'建议使用3 6 9 12这样排版',
    ),
    'open_author_info' => array(
        'labels' => 'tpl-article',
        'type' => 'checkon',
        'name' => '文章作者信息框（位于文章内容下方）',
        'values' => array('1'=>'开启'),
        'default' => '0',
    ),
    'comments_off' => array(
        'labels' => 'tpl-comment',
        'type' => 'checkon',
        'name' => '关闭全站评论',
        'values' => array('1'=>'开启'),
        'default' => '1',
    ),
    'diy_comment_btn' => array(
        'labels' => 'tpl-comment',
        'type' =>'text',
        'name' =>'自定义发送评论按钮文字',
        'values' => array('发送评论'),
        'description' =>'',
    ),
    'diy_comment_text' => array(
        'labels' => 'tpl-comment',
        'type' =>'text',
        'name' =>'自定义评论框内文字',
        'values' => array('你可以在这里输入评论内容...'),
        'description' =>'',
    ),
    'comnanes' => array(
        'labels' => 'tpl-comment',
        'type' =>'text',
        'name' =>'评论:管理员标志命名',
        'values' => array('博主'),
        'description' =>'管理员发表回复评论的标志',
    ),
    'comnanesu' => array(
        'labels' => 'tpl-comment',
        'type' =>'text',
        'name' =>'评论:注册会员标志命名',
        'values' => array('会员'),
        'description' =>'注册会员发表回复评论的标志',
    ),
    'comnaness' => array(
        'labels' => 'tpl-comment',
        'type' =>'text',
        'name' =>'评论:游客标志命名',
        'values' => array('游客'),
        'description' =>'游客发表回复评论的标志',
    ),
    'QQ' => array(
        'labels' =>'tpl-socialize',
        'type' =>'text',
        'name' =>'博主QQ',
        'values' => array(''),
        'description' =>'',
    ),
    'email' => array(
        'labels' =>'tpl-socialize',
        'type' =>'text',
        'name' =>'博主邮箱',
        'values' => array(''),
        'description' =>'',
    ),
    'weibo' => array(
        'labels' =>'tpl-socialize',
        'type' =>'text',
        'name' =>'博主微博',
        'values' => array(''),
        'description' =>'',
    ),
    'github' => array(
        'labels' =>'tpl-socialize',
        'type' =>'text',
        'name' =>'博主github',
        'values' => array(''),
        'description' =>'',
    ),
    'wx-img' =>array(
        'labels' =>'tpl-socialize',
        'type' => 'image',
        'name' => '博主微信二维码',
        'values' => array(
            TEMPLATE_URL . 'assets/images/logo.png',
        ),
        'description' => '上传文章页面作者微信二维码。'
    ),
    'gravatar_url' => array(
        'labels' => 'tpl-web',
        'type' => 'radio',
        'name' => 'Gravatar头像加速服务器',
        'values' => array(
            'cravatar' => 'cravatar服务器',
            'selected' => '萝莉服务器',
            'qiniu' => '七牛服务器',
            'geekzu' => '极客服务器',
            'cn' => '官方CN服务器',
            'ssl' => '官方SSL服务器',

        ),
        'default' => 'cravatar',
        'description' => '通过镜像服务器可对gravatar头像进行加速）。'
    ),
    'qqavatar_url' => array(
        'labels' => 'tpl-web',
        'type' => 'radio',
        'name' => 'QQ头像加速服务器',
        'values' => array(
            'Q1' => 'QQ官方服务器1',
            'Q2' => 'QQ官方服务器2',
            'Q3' => 'QQ官方服务器3',
            'Q4' => 'QQ官方服务器4',
        ),
        'default' => 'Q2',
        'description' => '（如果用户是QQ邮箱则调用QQ头像）。'
    ),
    'ui_cdn' => array(
        'labels' => 'tpl-web',
        'type' => 'radio',
        'new'          =>  'NEW',
        'name' => '选择前端外链CSS JS 图片加载节点',
        'values' => array(
            'local' => '本地（默认）',
            'diy_cdn' => '自建节点',
        ),
        'default' => 'local',
        'description' => ''
    ),
    'diy_cdn_src' => array(
        'labels' =>'tpl-web',
        'type' =>'text',
        'new'          =>  'NEW',
        'name' =>'自建节点链接',
        'values' => array(''),
        'multi' => true,
        'description' =>'把主题下的assets文件夹传到你自建的加速节点上填写上(如https://domain.com/lolimeow/assets)，链接结尾不要带“/”',
    ),


    'music-open' => array(
        'labels' => 'tpl-music',
        'type' => 'checkon',
        'name' => '全站底部音乐播放器开关',
        'values' => array('1'=>'开启'),
        'default' => '0',
    ),
    'music-type' => array(
        'labels' => 'tpl-music',
        'type' => 'radio',
        'name' => '音乐运营商类型',
        'values' => array(
            'netease' => '1.网易云',
            'tencent' => '2.QQ音乐',
            'kugou' => '3.酷狗',
            'xiami' => '4.虾米',
            'baidu' => '5.百度',
        ),
        'default' => 'netease',
    ),
    'music-id' => array(
        'labels' =>'tpl-music',
        'type' =>'text',
        'name' =>'歌单ID（歌单尽量不要那种超过100首的,API服务器可能会500）',
        'values' => array(''),
        'description' =>'',
    ),
    'music-list' => array(
        'labels' => 'tpl-music',
        'type' => 'radio',
        'name' => '播放方式',
        'values' => array(
            'list' => '顺序播放',
            'random' => '随机播放',
        ),
        'default' => 'list',
    ),
    'music-autoplay' => array(
        'labels' => 'tpl-music',
        'type' => 'radio',
        'name' => '浏览器自动播放',
        'values' => array(
            'false' => '关闭',
            'true' => '开启',
        ),
        'default' => 'false',
    ),
    'side_tag_num'   => [
        'labels'       => 'tpl-side',
        'type'         => 'text',
        'name'         => '标签显示数量',
        'pattern'      => 'num',
        'unit'         => '个',
        'default'      => '10',
        'min'          => '1',
        'description'  => ''
    ],
    'side_record_num'   => [
        'labels'       => 'tpl-side',
        'type'         => 'text',
        'name'         => '存档显示条数',
        'pattern'      => 'num',
        'unit'         => '个',
        'default'      => '10',
        'min'          => '1',
        'description'  => ''
    ],
    'side_nav_child' => array(
        'labels'       => 'tpl-side',
        'type' => 'checkon',
        'name' => '侧边分类二级分类展示',
        'values' => array('1'=>'开启'),
    ),
);