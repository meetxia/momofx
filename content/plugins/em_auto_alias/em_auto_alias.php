<?php
/*
Plugin Name: 自动生成文章别名
Version: 0.0.4
Plugin URL: https://www.emlog.net/plugin/detail/701
Description:根据标题自动生成文章的别名，汉字转为拼音。
Author: emlog
Author URL: https://www.emlog.net/plugin/index/author/577
*/

/********
 * 版权声明：
 * 版权所有 © [天津点滴记忆信息科技有限公司]
 * 本软件受到版权法和国际版权条约的保护。未经许可，任何人不得复制、修改、发布、出售、分发本软件的任何部分。
 * 未经许可，不得将本软件用于任何商业用途。任何人不得使用本软件进行任何违法活动。
 * 未经授权使用本软件的任何行为都将受到法律追究。
 * [天津点滴记忆信息科技有限公司] 保留所有权利。
 ********/

!defined('EMLOG_ROOT') && exit('access denied!');

class EmAutoAlias {

    //插件标识
    const ID = 'em_auto_alias';
    const NAME = '自动生成文章别名';
    const VERSION = '0.0.4';

    //实例
    private static $_instance;

    //数据库连接实例
    private $_db;

    //是否初始化
    private $_inited = false;

    //插件前端资源路径
    private $_assets;

    /**
     * 单例入口
     */
    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 私有构造函数，保证单例
     */
    private function __construct() {
    }

    /**
     * 初始化函数
     * @return void
     */
    public function init() {
        if ($this->_inited === true) {
            return;
        }
        $this->_inited = true;

        $this->_assets = BLOG_URL . 'content/plugins/' . self::ID . '/assets/';

        addAction('adm_footer', function () {
            EmAutoAlias::getInstance()->hookFooter();
        });
    }

    /**
     * 获取数据表
     */
    public function getTable($table = null) {
        return DB_PREFIX . 'em_ai_' . $table;
    }

    /**
     * 获取数据库连接
     */
    public function getDb() {
        if ($this->_db !== null) {
            return $this->_db;
        }
        $this->_db = Database::getInstance();
        return $this->_db;
    }

    /**
     * 菜单栏挂载函数
     * @return void
     */
    public function hookFooter() {
        echo sprintf(
            '<script src="%s"></script>',
            $this->_assets . 'main.js?ver=' . urlencode(self::VERSION . Option::EMLOG_VERSION_TIMESTAMP)
        );
        echo sprintf(
            '<script src="%s"></script>',
            $this->_assets . 'pinyin_dict_notone.js?ver=' . urlencode(self::VERSION . Option::EMLOG_VERSION_TIMESTAMP)
        );
        echo sprintf(
            '<script src="%s"></script>',
            $this->_assets . 'pinyinUtil.js?ver=' . urlencode(self::VERSION . Option::EMLOG_VERSION_TIMESTAMP)
        );
    }

}

EmAutoAlias::getInstance()->init();
