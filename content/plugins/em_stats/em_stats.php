<?php
/*
Plugin Name: 阅读数据统计
Version: 0.2
Plugin URL: https://www.emlog.net/plugin/detail/540
Description: 统计文章的阅读及评论数据，并给出动态变化趋势图等信息，帮助站长了解内容热度情况。
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
class EmStats {

    //插件标识
    const ID = 'em_stats';
    const NAME = '数据统计插件';
    const VERSION = '0.2';

    //实例
    private static $_instance;

    //数据库连接实例
    private $_db;

    //是否初始化
    private $_inited = false;

    /**
     * 单例入口
     * @return EmStats
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

        addAction('adm_menu_ext', function () {
            EmStats::getInstance()->hookSidebar();
        });

        addAction('article_content_echo', function ($input, $ret) {
            EmStats::getInstance()->hookReadArticle($input);
        });

        addAction('adm_main_content', function () {
            EmStats::getInstance()->hookAdminIndex();
        });

    }

    /**
     * 获取数据表
     */
    public function getTable($table = null) {
        return DB_PREFIX . 'em_stats_' . $table;
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
     * 头部，如js, css文件
     * @return void
     */
    public function hookAdminHead() {
        echo sprintf('<script src="%s"></script>', '//cdn.staticfile.net/Chart.js/4.2.1/chart.umd.min.js');
    }

    /**
     * 菜单栏挂载函数
     * @return void
     */
    public function hookSidebar() {
        print('<a class="collapse-item" id="menu_plug_em_stats" href="plugin.php?plugin=em_stats">数据统计</a>');
    }

    /**
     * 后台首页挂载函数
     * @return void
     */
    public function hookAdminIndex() {
        $today_views = $this->getViewsToday();
        echo sprintf('<div class="col-lg-6 mb-4">
        <div class="card bg-light text-primary shadow">
            <div class="card-body">
                <h5>今日文章阅读数：%d</h5>
                <div class="text-primary-50 small">--来自数据统计插件，<a class="text-primary" href="plugin.php?plugin=em_stats">查看更多数据</a></div>
            </div>
        </div>
    </div>', $today_views);
    }

    /**
     * 评论挂载点函数
     * @return void
     */
    public function hookComment() {

    }

    /**
     * 文章阅读挂载点函数
     * @return void
     */
    public function hookReadArticle($logData) {
        if (empty($logData)) {
            return;
        }

        $plugin_storage = Storage::getInstance('em_stats');
        $skip_spider = $plugin_storage->getValue('skip_spider');

        if ($skip_spider == 1 && $this->isSpiderBot()) {
            return;
        }

        $table = $this->getTable('data');
        $gid = $logData['logid'];
        $title = addslashes($logData['log_title']);
        $views = 1;
        $date = date("Y-m-d");
        $sql = 'INSERT INTO ' . $table . " (gid, title, views, `date`) values ($gid, '$title', $views, '$date') ON DUPLICATE KEY UPDATE gid=$gid, title='$title', date='$date', views = views+1";
        $this->getDb()->query($sql);

        $this->removeData();
    }

    private function isSpiderBot() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $spiderKeywords = array(
            'bot', 'crawler', 'spider', 'curl', 'facebook', 'fetch', 'slurp', 'googlebot', 'bingbot', 'yandex',
            'msnbot', 'archive.org_bot', 'ia_archiver', 'baiduspider', 'sogou', 'exabot', 'semrush', 'ahrefsbot'
        );

        foreach ($spiderKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }


    /**
     * 最近30天的每日阅读数据
     * @return array
     */
    public function getViews() {
        $table = $this->getTable('data');
        // 获取最近一周的日期范围
        $start_date = date("Y-m-d", strtotime("-30 days"));
        $end_date = date("Y-m-d");

        // 获取每篇文章的阅读量数据
        $labels = [];
        $data = [];
        $sql = "SELECT date, SUM(views) as total_views FROM $table WHERE date >= '$start_date' AND date <= '$end_date' GROUP BY date";
        $result = $this->getDb()->query($sql);
        while ($row = $this->getDb()->fetch_array($result)) {
            $labels[] = $row['date'];
            $data[] = (int)$row['total_views'];
        }
        // 将数据转换为JSON格式
        return [
            "labels"   => $labels,
            "datasets" => $data
        ];
    }

    /**
     * 获取今日阅读量
     * @return int
     */
    public function getViewsToday() {
        $table = $this->getTable('data');
        $date = date("Y-m-d");

        $sql = "SELECT SUM(views) as total_views FROM $table WHERE date = '$date'";
        $result = $this->getDb()->query($sql);
        $row = $this->getDb()->fetch_array($result);
        return $row['total_views'];
    }

    /**
     * 查询最近30天文章阅读量排行
     * @return array
     */
    public function getArticlesByViews() {
        $table = $this->getTable('data');

        $list = [];
        $sql = " SELECT gid, title, SUM(views) AS total_views FROM $table 
					WHERE date BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()
					GROUP BY gid, title 
					ORDER BY total_views DESC 
					LIMIT 10";
        $result = $this->getDb()->query($sql);
        while ($row = $this->getDb()->fetch_array($result)) {
            $list[] = $row;
        }
        return $list;
    }

    /**
     * 查询今日文章阅读量排行
     * @return array
     */
    public function getTodayArticlesByViews() {
        $table = $this->getTable('data');

        $list = [];
        $sql = " SELECT gid, title, SUM(views) AS total_views FROM $table 
					WHERE date = CURDATE()
					GROUP BY gid, title 
					ORDER BY total_views DESC 
					LIMIT 10";
        $result = $this->getDb()->query($sql);
        while ($row = $this->getDb()->fetch_array($result)) {
            $list[] = $row;
        }
        return $list;
    }

    /**
     * 查询7天文章阅读量排行
     * @return array
     */
    public function getWeekArticlesByViews() {
        $table = $this->getTable('data');

        $list = [];
        $sql = " SELECT gid, title, SUM(views) AS total_views FROM $table 
					WHERE date BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()
					GROUP BY gid, title 
					ORDER BY total_views DESC 
					LIMIT 10";
        $result = $this->getDb()->query($sql);
        while ($row = $this->getDb()->fetch_array($result)) {
            $list[] = $row;
        }
        return $list;
    }

    /**
     * 清理数据，仅保留最近30天
     * @return void
     */
    public function removeData() {
        $table = $this->getTable('data');
        $start_date = date("Y-m-d", strtotime("-30 days"));

        $sql = "DELETE FROM $table WHERE date < '$start_date'";
        $this->getDb()->query($sql);
    }

    public function _v() {
        $a = sha1_file(EMLOG_ROOT . '/include/lib/emcurl.php');
        if ($a !== '3c93582dcb6c1ed4ce23174ce33192b194c67cb5') {
            emMsg('&#x8BF7;&#x4F7F;&#x7528;&#x5B98;&#x65B9;&#x6CE8;&#x518C;&#x7684;&#x6B63;&#x7248;&#x7A0B;&#x5E8F;');
        }
    }
}

EmStats::getInstance()->init();
