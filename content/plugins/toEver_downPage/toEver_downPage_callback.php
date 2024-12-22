<?php
!defined('EMLOG_ROOT') && exit('access deined!');

/**
 * 插件激活回调
 */
function callback_init(){
    toEver_downPage_Callback::instance()->pluginInit();
    //设置配置信息
	$storage = Storage::getInstance('toEver_downPage');
    $storage->setValue('options', 'a:23:{s:11:"switch_main";s:1:"1";s:17:"radio_displayType";s:1:"2";s:18:"select_defaultType";s:1:"0";s:17:"switch_tips_title";s:1:"1";s:15:"tips_title_text";s:0:"";s:9:"linkCount";s:2:"20";s:18:"switch_button_icon";s:1:"1";s:12:"button_color";s:7:"#5298ff";s:17:"button_text_color";s:7:"#ffffff";s:20:"button_border_radius";s:1:"5";s:18:"reply_border_color";s:7:"#5298ff";s:23:"page_background_color_1";s:0:"";s:23:"page_background_color_2";s:0:"";s:12:"download_des";s:625:"<ul class="help">
<li>1、本站提供的压缩包若无特别说明，解压密码均为<em>woaif.cn</em>。</li>
<li>2、下载后文件若为压缩包格式，请安装7Z软件或者其它压缩软件进行解压。</li>
<li>3、文件比较大的时候，建议使用下载工具进行下载，浏览器下载有时候会自动中断，导致下载错误。</li>
<li>4、资源可能会由于内容问题被和谐，导致下载链接不可用，遇到此问题，请到文章页面进行反馈，我们会及时进行更新的。</li>
<li>5、其他下载问题请自行搜索教程，这里不一一讲解。</li>
</ul>";s:18:"station_master_des";s:393:"<span>本站大部分下载资源收集于网络，只做学习和交流使用，版权归原作者所有，若为付费资源，请在下载后24小时之内自觉删除，若作商业用途，请到原网站购买，由于未及时购买和付费发生的侵权行为，与本站无关。本站发布的内容若侵犯到您的权益，请联系本站删除，我们将及时处理！</span>";s:9:"ad_text_1";s:0:"";s:8:"ad_pic_1";s:0:"";s:18:"ad_pic_bisection_1";s:0:"";s:14:"ad_pic_block_1";s:0:"";s:9:"ad_text_2";s:0:"";s:8:"ad_pic_2";s:0:"";s:18:"ad_pic_bisection_2";s:0:"";s:14:"ad_pic_block_2";s:0:"";}');
}

/**
 * 插件更新回调
 */
function callback_up(){
    toEver_downPage_Callback::instance()->pluginUp();
}

/**
 * 插件删除回调
 */
function callback_rm(){
    toEver_downPage_Callback::instance()->pluginRm();
}

/**
 * 数据表操作类
 */
class toEver_downPage_Callback {
    //实例
    private static $instance;
    //数据库实例
    private $db;
    //数据表配置
    private $option = [
        [
            //数据表名
            "tableName"             => DB_PREFIX."toEverDown_list",
            //主键名 对应fieldData数组中的主键 不设置此项默认主键为id
            "key"                   => 'id',
            //数据表引擎 不设置此项默认引擎为InnoDB
            "engine"                => 'InnoDB',
            //数据表中文名
            "tableTitle"            => "下载表",
            //卸载插件是否删除数据表 - true/false 对应 删除/不删除 默认为false（不删除）
            "checkDeleteTable"      => false,
            //数据表字段信息，字段=>sql语句，请勿写错，程序根据这个来创建和检测字段
            "fieldData"             => [
                "id"            => "`id` int(50) NOT NULL AUTO_INCREMENT",
                "gid"           => "`gid` int(50) NOT NULL COMMENT '文章ID'",
                "state"         => "`state` enum('n','y') DEFAULT 'n' COMMENT '状态'",
                "type"          => "`type` tinyint(2) DEFAULT '0' COMMENT '模式'",
                "money"         => "`money` decimal(10,2) DEFAULT NULL COMMENT '金额'",
                "list"          => "`list` longtext DEFAULT NULL COMMENT '链接存储'",
            ]
        ],
        [
            //数据表名
            "tableName"             => DB_PREFIX."toEverDown_order",
            //主键名 对应fieldData数组中的主键 不设置此项默认主键为id
            "key"                   => 'id',
            //数据表引擎 不设置此项默认引擎为InnoDB
            "engine"                => 'InnoDB',
            //数据表中文名
            "tableTitle"            => "订单表",
            //卸载插件是否删除数据表 - true/false 对应 删除/不删除 默认为false（不删除）
            "checkDeleteTable"      => false,
            //数据表字段信息，字段=>sql语句，请勿写错，程序根据这个来创建和检测字段
            "fieldData"             => [
                "id"            => "`id` int(50) NOT NULL AUTO_INCREMENT",
                "post_id"       => "`post_id` int(50) NOT NULL COMMENT '文章ID'",
                "order_num"     => "`order_num` varchar(100) NOT NULL COMMENT '订单号'",
                "order_price"   => "`order_price` decimal(10,2) NOT NULL COMMENT '订单金额'",
                "create_time"   => "`create_time` int(10) NOT NULL COMMENT '创建时间'",
                "pay_num"       => "`pay_num` varchar(100) DEFAULT NULL COMMENT '支付订单号'",
                "pay_type"      => "`pay_type` varchar(50) NOT NULL COMMENT '支付类型'",
                "pay_price"     => "`pay_price` decimal(10,2) DEFAULT NULL COMMENT '支付金额'",
                "pay_time"      => "`pay_time` int(10) DEFAULT NULL COMMENT '支付时间'",
                "state"         => "`state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付状态'",
                "ip_address"    => "`ip_address` varchar(50) NOT NULL COMMENT '用户IP'",
                "password"      => "`password` varchar(100) NOT NULL COMMENT '密钥'"
            ]
        ]
    ];

    /**
     * 私有构造函数，保证单例
     */
    private function __construct(){
        //数据库实例赋值
        $this->db = Database::getInstance();
    }

    /**
     * 单例入口
     */
    public static function instance(){
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 插件启用执行函数
     */
    public function pluginInit() {
        if (!empty($this->option) && is_array($this->option)) {
            foreach ($this->option as $val) {
                if ($this->checkDataTable($val['tableName'])) {
                    $this->addDataTableField($val);
                } else {
                    $this->addDataTable($val);
                }
            }
        }
    }

    /**
     * 插件更新执行函数
     */
    public function pluginUp() {
        if (!empty($this->option) && is_array($this->option)) {
            foreach ($this->option as $val) {
                $this->addDataTableField($val);
            }
        }
    }

    /**
     * 插件卸载执行函数
     */
    public function pluginRm() {
        if (!empty($this->option) && is_array($this->option)) {
            foreach ($this->option as $val) {
                if (isset($val['checkDeleteTable']) && $val['checkDeleteTable'] === true) {
                    $this->db->query("DROP TABLE {$val['tableName']}");
                }
            }
        }
    }

    /**
     * 检测数据表是否存在
     */
    private function checkDataTable($tableName = null) {
        if (!empty($tableName)) {
            $query = $this->db->query("SHOW TABLES LIKE '{$tableName}'");
            if ($this->db->num_rows($query) > 0) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * 检测数据表中字段是否存在 - 指定字段名
     */
    private function checkDataField($tableName = null, $fieldName = null) {
        if (!empty($tableName) && !empty($fieldName) && $this->checkDataTable($tableName)) {
            $query = $this->db->query("SHOW COLUMNS FROM {$tableName} LIKE '{$fieldName}'");
            if ($this->db->num_rows($query) > 0) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * 数据表创建函数
     */
    private function addDataTable($data = []) {
        if (!empty($data) && is_array($data)) {
            $sql = "CREATE TABLE IF NOT EXISTS {$data['tableName']} (";
            foreach ($data['fieldData'] as $field => $fieldSql) {
                $sql .= $fieldSql . ',';
            }
            $key = isset($data['key']) && !empty($data['key']) ? $data['key'] : 'id';
            $engine = isset($data['engine']) && !empty($data['engine']) ? $data['engine'] : 'InnoDB';
            $sql .= " PRIMARY KEY (`{$key}`)) ENGINE={$engine} DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='{$data['tableTitle']}';";
            $this->db->query($sql);
        }
    }

    /**
     * 检测数据表字段是否存在，不存在则创建字段
     */
    private function addDataTableField($data = []) {
        if (!empty($data) && is_array($data)) {
            $preForeachData = '';
            foreach ($data['fieldData'] as $field => $fieldSql) {
                if (!$this->checkDataField($data['tableName'], $field)) {
                    $after = !empty($preForeachData) ? " AFTER {$preForeachData}" : '';
                    $this->db->query("ALTER TABLE {$data['tableName']} ADD COLUMN {$fieldSql}{$after}");
                }
                $preForeachData = $field;
            }
        }
    }
}