<?php
/*
Plugin Name: AI助手
Version: 0.3
Plugin URL: https://www.emlog.net/plugin/detail/659
Description: 基于百度文心一言大语言模型，提供自动生成文章等AI功能。
Author: emlog
Author URL: https://www.emlog.net/author/index/577
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

class EmAI {

    //插件标识
    const ID = 'em_ai';
    const NAME = 'AI助手';
    const VERSION = '0.3';

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
     * @return EmAI
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

        $plugin_storage = Storage::getInstance('em_ai');

        $open2all = $plugin_storage->getValue('open2all');

        if ($open2all !== 'y' && !User::haveEditPermission()) {
            return;
        }

        addAction('adm_menu_ext', function () {
            EmAI::getInstance()->hookSidebar();
        });

        addAction('adm_footer', function () {
            EmAI::getInstance()->hookFooter();
        });

        addAction('adm_head', function () {
            EmAI::getInstance()->hookHeader();
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

    public function hookHeader() {
        echo <<< EOT
<style>
        .expandable-div {
            display: none;
            border: 2px dashed #999;
            border-radius: 10px;
            padding: 10px;
            margin-top: 10px;
        }

        .expandable-div a {
            margin-bottom: 5px;
        }
    </style>
EOT;

    }

    /**
     * 菜单栏挂载函数
     * @return void
     */
    public function hookSidebar() {
        print('<a class="collapse-item" id="menu_plug_em_ai" href="plugin.php?plugin=em_ai">AI助手</a>');
    }

    /**
     * 菜单栏挂载函数
     * @return void
     */
    public function hookFooter() {
        $access_token = $this->getAccessToken();

        $plugin_storage = Storage::getInstance('em_ai');

        $temper = $plugin_storage->getValue('temper');
        $model = $plugin_storage->getValue('model');

        $defaultTemper = 0.8;
        $temper = empty($temper) || $temper > 1 || $temper <= 0 ? $defaultTemper : $temper;
        $apiUrl = $this->getApiUrl($model);

        // JavaScript for AI助手
        echo <<<EOT
<script>
    $(document).ready(function() {
        var uploadLink = $('a[href="#mediaModal"]');
        var newLink = $('<a href="#" class="ml-3" id="newLink">AI助手</a>');
        uploadLink.after(newLink);

        var expandableDiv = $('<div class="expandable-div">' +
            '<p><a href="#" class="" id="gen_article">根据标题生成文章</a> | <a href="#" class="" id="regen_article">文章润色（二次创作）</a>  | <a href="#" class="" id="gen_title">自动生成标题</a> | <a href="#" class="" id="refine_article">自动生成摘要</a> | <a href="#" class="" id="gen_tags">自动生成标签</a> | <a href="#" class="" id="gen_cover">🖼️自动生成封面图</a></p></p>' +
            '<p id="em_ai_message"></p>' +
            '<small>AI助手插件，基于百度智能云模型：$model </small>' +
            '</div>');
        newLink.after(expandableDiv);

        newLink.on('click', function () {
            expandableDiv.slideToggle();
        });

        $('#gen_article').on('click', function() {
            generateArticle("$access_token", $temper, "$apiUrl");
        });
        $('#regen_article').on('click', function() {
            regenerateArticle("$access_token", $temper, "$apiUrl");
        });
        $('#gen_title').on('click', function() {
            genTitle("$access_token", $temper, "$apiUrl");
        });
        $('#refine_article').on('click', function() {
            refineArticle("$access_token");
        });
        $('#gen_tags').on('click', function() {
            genTags("$access_token");
        });
        $('#gen_cover').on('click', function() {
            genCover("$access_token");
        });
    });
</script>
EOT;

        // Include main.js with version
        echo sprintf(
            '<script src="%s"></script>',
            $this->_assets . 'main.js?ver=' . urlencode(self::VERSION . Option::EMLOG_VERSION_TIMESTAMP)
        );
        echo "\n";

    }

    public function getAccessToken() {
        $plugin_storage = Storage::getInstance('em_ai');

        $access_token = $plugin_storage->getValue('access_token');
        $access_token_create_at = $plugin_storage->getValue('access_token_create_at');

        if ($access_token && $access_token_create_at && time() - $access_token_create_at < 25 * 24 * 3600) {
            return $access_token;
        }

        $ak = $plugin_storage->getValue('ak');
        $sk = $plugin_storage->getValue('sk');

        if (empty($ak) || empty($sk)) {
            return '';
        }

        $clientId = $ak;
        $clientSecret = $sk;
        $url = "https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id={$clientId}&client_secret={$clientSecret}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response, true);
        $access_token = isset($json['access_token']) ? $json['access_token'] : '';
        if ($access_token) {
            $plugin_storage->setValue('access_token', $access_token);
            $plugin_storage->setValue('access_token_create_at', time());
        }

        return $access_token;
    }

    // 模型：ERNIE-Bot-turbo
    // 百度文档：https://cloud.baidu.com/doc/WENXINWORKSHOP/s/4lilb2lpf
    public function chat() {
        ob_start();
        $accessToken = $this->getAccessToken();
        $url = "https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/eb-instant?access_token={$accessToken}";

        $data = array(
            "messages" => array(
                array(
                    "role"    => "user",
                    "content" => "给我推荐一些自驾游路线"
                )
            ),
            "stream"   => true
        );

        $payload = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) {
            echo $data;
            ob_flush();
            flush();
        });
        curl_exec($ch);
        curl_close($ch);
    }

    public function write($model, $prompt = '') {
        $accessToken = $this->getAccessToken();
        if (empty($accessToken)) {
            return '';
        }
        $apiUrl = $this->getApiUrl($model) . "?access_token=$accessToken";

        $data = array(
            "messages" => array(
                array(
                    "role"    => "user",
                    "content" => $prompt
                )
            ),
            "stream"   => false
        );

        $payload = json_encode($data);

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // 获取返回的响应
        $resp = curl_exec($ch);

        // 检查是否有错误
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return json_encode(array("error" => $error_msg));
        }

        curl_close($ch);

        // 解析响应为 JSON
        $responseData = json_decode($resp, true);

        return $responseData;
    }

    // 文生图
    // 模型：Stable-Diffusion-XL是业内知名的跨模态大模型，由StabilityAI研发并开源，有着业内领先的图像生成能力。本文介绍了相关API。
    // 百度文档：https://cloud.baidu.com/doc/WENXINWORKSHOP/s/Klkqubb9w
    public function text2image($prompt) {
        $accessToken = $this->getAccessToken();
        if (empty($accessToken) || empty($prompt)) {
            return false;
        }

        $apiUrl = "https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/text2image/sd_xl?access_token=$accessToken";

        $data = array(
            "prompt"          => $prompt,
            "size"            => '768x768',
            "negative_prompt" => 'white',
            "steps"           => 20,
            "n"               => 1,
        );

        $payload = json_encode($data);

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // 获取返回的响应
        $resp = curl_exec($ch);

        // 检查是否有错误
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return json_encode(array("error" => $error_msg));
        }

        curl_close($ch);

        // 解析响应为 JSON
        $responseData = json_decode($resp, true);

        if (isset($responseData['data'][0]['b64_image'])) {
            $b64_image = $responseData['data'][0]['b64_image'];
            $image_data = base64_decode($b64_image);

            $dirPath = 'content/uploadfile/text2image';
            if (!file_exists($dirPath)) {
                mkdir(EMLOG_ROOT . '/' . $dirPath, 0755, true);
            }

            // 生成唯一的文件名
            $fileName = uniqid() . '.png';
            $filePath = $dirPath . '/' . $fileName;

            // 保存文件
            if (!file_put_contents(EMLOG_ROOT . '/' . $filePath, $image_data)) {
                return false;
            }

            return '../' . $filePath;
        } else {
            return json_encode(array("error" => "No image data found in response"));
        }
    }

    public function getApiUrl($model) {
        switch ($model) {
            case 'ERNIE-Bot-turbo':
                return 'https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/ernie-lite-8k';
            case 'ERNIE-Bot-4':
                return 'https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/completions_pro';
            case 'ERNIE-3.5-128K':
                return 'https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/ernie-3.5-128k';
            case 'Meta-Llama-3-70':
                return 'https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/llama_3_70b';
            case 'ERNIE-Speed-128K':
                return 'https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/ernie-speed-128k';
            default:
                return 'https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/eb-instant';
        }
    }
}

EmAI::getInstance()->init();
