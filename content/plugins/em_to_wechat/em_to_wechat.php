<?php
/*
Plugin Name: 文章复制到公众号
Version: 0.0.3
Plugin URL: https://www.emlog.net/plugin/detail/703
Description:将文章转换排版成微信公众号格式，并支持一键复制粘贴到公众号后台。
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

class EmToWechat {

    //插件标识
    const ID = 'em_to_wechat';
    const NAME = '文章复制到公众号';
    const VERSION = '0.0.3';

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

        addAction('adm_head', function () {
            EmToWechat::getInstance()->hookHeader();
        });
        addAction('adm_footer', function () {
            EmToWechat::getInstance()->hookFooter();
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

    public function hookFooter() {
        echo <<<EOT
<div class="modal" id="wechatModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <a href="javascript:void(0);" class="copy" data-clipboard-action="copy" data-clipboard-target="#em_log_content">复制文章</a> （复制后可直接到公众号后台粘贴）
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between">
                    <div id="em_log_content" class="markdown px-5"></div>
                </div>
            </div>
        </div>
    </div>
</div>
EOT;
        echo sprintf(
            '<script src="%s"></script>',
            $this->_assets . 'main.js?ver=' . urlencode(self::VERSION . Option::EMLOG_VERSION_TIMESTAMP)
        );
        echo sprintf(
            '<script src="%s"></script>',
            $this->_assets . 'clipboard.min.js?ver=' . urlencode(self::VERSION . Option::EMLOG_VERSION_TIMESTAMP)
        );
    }

    public function hookHeader() {
        echo sprintf(
            '<link rel="stylesheet" type="text/css" href="%s">',
            $this->_assets . 'main.css?ver=' . urlencode(self::VERSION . Option::EMLOG_VERSION_TIMESTAMP)
        );
    }

    public function getContent($id) {
        $Log_Model = new Log_Model();
        $r = $Log_Model->getOneLogForHome($id, true);
        $content = $r['log_content'];
        Output::ok($content);
    }

}

EmToWechat::getInstance()->init();
