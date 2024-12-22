<?php
/*
Plugin Name: 微信分享带图
Version: 1.0
Plugin URL:
Description: 微信内分享页面会带缩略图
Author: 硬汉工作室
Author URL: http://www.nciaer.com
*/

defined('EMLOG_ROOT') || exit('access denied!');

function nciaer_weixin_share() {

    $pconfig = Storage::getInstance('nciaer_weixin_share');
    $appid = (string)$pconfig->getValue('appid');
    $appsecret = (string)$pconfig->getValue('appsecret');
    if(empty($appid) || empty($appsecret)) return;

    $logo = (string)$pconfig->getValue('logo');
    $debug = (int)$pconfig->getValue('debug') ? 'true':'false';
    $desc = (string)$pconfig->getValue('desc');
    $jssdk = new NciaerWeiXinShare($appid, $appsecret, 3600);
    $sign = $jssdk->GetSignPackage();

    $logid = nciaer_weixin_share_getLogId();
    if($logid) {
        $Log_Model = new Log_Model();
        $logData = $Log_Model->getOneLogForHome($logid, true, true);
        if($logData && is_array($logData)) {
            if(!empty($logData['log_cover'])) {
                $logo = $logData['log_cover'];
            }
            if(!empty($logData['excerpt'])) {
                $desc = strip_tags($logData['excerpt']);
            }
        }
    }

    echo <<<HTML
<script src="https://res.wx.qq.com/open/js/jweixin-1.6.0.js"></script>
<script>
    wx.config({
        debug: {$debug},
        appId: '{$appid}',
        timestamp: {$sign["timestamp"]},
        nonceStr: '{$sign["nonceStr"]}',
        signature: '{$sign["signature"]}',
        jsApiList: ['updateAppMessageShareData', 'updateTimelineShareData']
    });
    wx.ready(function () {
        wx.updateAppMessageShareData({
            title: document.title,
            desc: '{$desc}',
            link: location.href,
            imgUrl: '{$logo}'
        })
        wx.updateTimelineShareData({
            title: document.title,
            link: location.href,
            imgUrl: '{$logo}'
        })
    });
</script>
<!--{/block}-->
HTML;
}

addAction('index_head', 'nciaer_weixin_share');

class NciaerWeiXinShare {
    private $appId;
    private $appSecret;
    private $cachetime;

    public function __construct($appId, $appSecret, $cachetime) {

        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->cachetime = $cachetime;
    }
    public function getSignPackage() {

        $jsapiTicket = $this->getJsApiTicket();
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);

        $signPackage = array("nonceStr" => $nonceStr, "timestamp" => $timestamp, "url" => $url, "signature" => $signature, "rawString" => $string);
        return $signPackage;
    }

    private function getJsApiTicket() {

        $pconfig = Storage::getInstance('nciaer_weixin_share');
        $ticket = (string)$pconfig->getValue('ticket');
        $ticketTime = (int)$pconfig->getValue('ticketTime');
        if(empty($ticket) || $ticketTime < time()) {
            $ticket = $this->updateTicket();
        }

        return $ticket;
    }

    private function updateTicket() {

        $pconfig = Storage::getInstance('nciaer_weixin_share');
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
        $res = json_decode(file_get_contents($url));
        $ticket = $res->ticket;
        if ($ticket) {
            $ticketTime = time() + $this->cachetime;
            $pconfig->setValue('ticket', $ticket);
            $pconfig->setValue('ticketTime', $ticketTime);

        }
        return $ticket;
    }

    private function getAccessToken() {

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
        $res = json_decode(file_get_contents($url));
        $access_token = $res->access_token;
        return $access_token;
    }


    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}

function nciaer_weixin_share_getLogId() {

    $path = Dispatcher::setPath();
    $routingTable = Option::getRoutingTable();
    $urlMode = Option::get('isurlrewrite');

    $params = array();

    foreach ($routingTable as $route) {
        $reg = isset($route['reg_' . $urlMode]) ? $route['reg_' . $urlMode] : (isset($route['reg']) ? $route['reg'] : $route['reg_0']);
        if (preg_match($reg, $path, $matches)) {
            $params = $matches;
            break;
        }

        if (preg_match($route['reg_0'], $path, $matches)) {

            $params = $matches;
            break;
        }
    }

    $logid = 0;
    if (isset($params[1])) {
        if ($params[1] == 'post') {
            $logid = isset($params[2]) ? (int)$params[2] : 0;
        } elseif (is_numeric($params[1])) {
            $logid = (int)$params[1];
        } else {
            $CACHE = Cache::getInstance();
            $logalias_cache = $CACHE->readCache('logalias');
            if (!empty($logalias_cache)) {
                $alias = addslashes(urldecode(trim($params[1])));
                $logid = array_search($alias, $logalias_cache);
                if (!$logid) {
                    $logid = 0;
                }
            }
        }
    }

    return $logid;
}