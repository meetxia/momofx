<?php
/*
Plugin Name: TDK自定义
Version: 0.1
Plugin URL: https://www.emlog.net/plugin/detail/540
Description: 自定义文章和页面的TDK信息
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

/**
 * 数据统计类
 */
class EmTdk {

    //插件标识
    const ID = 'em_tdk';
    const NAME = 'TDK自定义';
    const VERSION = '0.1';

    //实例
    private static $_instance;

    //数据库连接实例
    private $_db;

    //是否初始化
    private $_inited = false;

    /**
     * 单例入口
     * @return EmTdk
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

        addAction('adm_writelog_side', function () {
            EmTdk::getInstance()->hookArticleSide();
        });

        addAction('adm_write_page_side', function () {
            EmTdk::getInstance()->hookArticleSide();
        });

        addAction('save_log', function ($blogid) {
            EmTdk::getInstance()->hookSaveArticle($blogid);
        });

        addAction('save_page', function ($blogid) {
            EmTdk::getInstance()->hookSaveArticle($blogid);
        });

        addAction('article_content_echo', function ($logData, &$retData) {
            EmTdk::getInstance()->hookArticleEcho($logData, $retData);
        });

    }

    /**
     * 获取数据表
     */
    public function getTable($table = null) {
        return DB_PREFIX . 'em_tdk_' . $table;
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
     * 写文章页侧边栏挂载函数
     * @return void
     */
    public function hookArticleSide() {
        $gid = Input::getIntVar('gid');
        if (!$gid) {
            $gid = Input::getIntVar('id');// page
        }
        $tdk = $this->getTdk($gid);
        $t = $d = $k = '';
        if ($tdk) {
            $t = $tdk['t'];
            $d = $tdk['d'];
            $k = $tdk['k'];
        }
        echo <<<EOT
            <div class="form-group">
                <p>SEO（自定义TDK插件）</p>
                <label>自定义标题</label>
                <input name="em_tdk_title" id="em_tdk_title" class="form-control" value="$t"/>
                <label>自定义描述</label>
                <textarea name="em_tdk_desc" id="em_tdk_desc" class="form-control" row="3">$d</textarea>
                <label>自定义关键词</label>
                <input name="em_tdk_kw" id="em_tdk_kw" class="form-control" value="$k"/>
                <small>多个用英文逗号分割</small>
            </div>
EOT;
    }

    /**
     * 文章保存挂载点函数
     * @return void
     */
    public function hookSaveArticle($blogid) {
        $em_tdk_title = Input::postStrVar('em_tdk_title');
        $em_tdk_desc = Input::postStrVar('em_tdk_desc');
        $em_tdk_kw = Input::postStrVar('em_tdk_kw');

        $table = $this->getTable('data');
        $sql = 'INSERT INTO ' . $table . " (gid, t, d, k) values ($blogid, '$em_tdk_title', '$em_tdk_desc', '$em_tdk_kw') ON DUPLICATE KEY UPDATE t='$em_tdk_title', d='$em_tdk_desc', k='$em_tdk_kw'";
        $this->getDb()->query($sql);
    }

    public function hookArticleEcho($logData, &$retData) {
        $gid = $logData['logid'];
        $tdk = $this->getTdk($gid);
        $t = $d = $k = '';
        if ($tdk) {
            $t = $tdk['t'];
            $d = $tdk['d'];
            $k = $tdk['k'];
        }

        if ($t) {
            $retData['site_title'] = $t;
        }
        if ($d) {
            $retData['site_description'] = $d;
        }
        if ($k) {
            $retData['site_key'] = $k;
        }
    }

    private function getTdk($gid) {
        $table = $this->getTable('data');

        $sql = "SELECT *  FROM $table WHERE gid = $gid";
        $result = $this->getDb()->query($sql);
        return $this->getDb()->fetch_array($result);
    }
}

EmTdk::getInstance()->init();
