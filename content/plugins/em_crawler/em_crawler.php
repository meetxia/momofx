<?php
/*
Plugin Name: 爬虫记录
Version: 0.0.7
Plugin URL: https://www.emlog.net/plugin/detail/570
Description: 识别并记录访问过站点的机器人爬虫（包括但不限于搜索引擎蜘蛛），支持识别1000多种机器爬虫。
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

require_once __DIR__ . '/Fixtures/AbstractProvider.php';
require_once __DIR__ . '/Fixtures/Crawlers.php';
require_once __DIR__ . '/Fixtures/Exclusions.php';
require_once __DIR__ . '/Fixtures/Headers.php';
require_once __DIR__ . '/CrawlerDetect.php';

class EmCrawler {

    //实例
    private static $_instance;

    //数据库连接实例
    private $_db;

    //是否初始化
    private $_inited = false;

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

        addAction('index_head', function () {
            EmCrawler::getInstance()->hookCrawler();
        });

        addAction('adm_menu_ext', function () {
            EmCrawler::getInstance()->hookSidebar();
        });

        addAction('adm_main_content', function () {
            EmCrawler::getInstance()->hookAdminIndex();
        });
    }

    /**
     * 获取数据表
     */
    public function getTable($table = null) {
        return DB_PREFIX . 'em_crawler_' . $table;
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

    public function hookCrawler() {
        $table = $this->getTable('data');
        $CrawlerDetect = new CrawlerDetect;
        if ($CrawlerDetect->isCrawler()) {
            $crawlerName = $CrawlerDetect->getMatches();
            $ua = $CrawlerDetect->getUserAgent();
            $ip = getIp();
            $url = rtrim(BLOG_URL, "\/") . $_SERVER['REQUEST_URI'];
            $date = date("Y-m-d H:i:s");
            $crawlerDetail = 'UA: ' . $ua . '<br> IP: ' . $ip;

            $sql = 'INSERT INTO ' . $table . " (`name`, `ua`, `url`, `date`) values ('$crawlerName', '$crawlerDetail', '$url', '$date')";
            $this->getDb()->query($sql);
            $this->removeData();
        }
    }

    /**
     * 菜单栏挂载函数
     * @return void
     */
    public function hookSidebar() {
        print('<a class="collapse-item" id="menu_plug_em_crawler" href="plugin.php?plugin=em_crawler">爬虫记录</a>');
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
                <h5>今日爬虫来访数：%d</h5>
                <div class="text-primary-50 small">--来自爬虫记录插件，<a class="text-primary" href="plugin.php?plugin=em_crawler">查看更多数据</a></div>
            </div>
        </div>
    </div>', $today_views);
    }

    /**
     * 获取爬虫记录
     * @return array
     */
    public function getList($crawler) {

        $q = '1 = 1';
        switch ($crawler) {
            case 'baidu':
                $q = "name = 'Baiduspider'";
                break;
            case 'google':
                $q = "name = 'Googlebot'";
                break;
            case 'sogou':
                $q = "name LIKE '%Sogou%'";
                break;
            case 'bing':
                $q = "name = 'bingbot'";
                break;
            case '360':
                $q = "name = '360Spider'";
                break;
            case '':
            default:
                break;
        }

        $table = $this->getTable('data');

        $data = [];
        $sql = "SELECT * FROM `$table` WHERE $q ORDER BY `id` DESC LIMIT 800";
        $result = $this->getDb()->query($sql);
        while ($row = $this->getDb()->fetch_array($result)) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * 获取今日阅读量
     * @return int
     */
    public function getViewsToday() {
        $table = $this->getTable('data');

        $sql = "SELECT count(*) as total_views FROM $table WHERE date >= CURDATE()";
        $result = $this->getDb()->query($sql);
        $row = $this->getDb()->fetch_array($result);
        return $row['total_views'];
    }

    /**
     * 清理数据，仅保留最近7天
     * @return void
     */
    public function removeData() {
        $table = $this->getTable('data');
        $start_date = date("Y-m-d", strtotime("-7 days"));

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

EmCrawler::getInstance()->init();

