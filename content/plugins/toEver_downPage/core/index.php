<?php
!defined('EMLOG_ROOT') && exit();
//加载类库
require_once TOEVER_DOWNPAGE_ROOT . '/core/vendor/autoload.php';

//载入数据库类
use Medoo\Medoo;

//定义db类全局变量
global $xfdb;

//初始化全局变量
$xfdb = new Medoo([
    'database_type'	=> 'mysql',
    'database_name'	=> DB_NAME,
    'server'		=> DB_HOST,
    'username'		=> DB_USER,
    'password'		=> DB_PASSWD,
    'prefix'		=> DB_PREFIX,
    'charset'		=> 'utf8',
]);

//控制器类
class toEverDownPage {

    //实例
    private static $_instance;
    //数据库实例
    private $db;
    //数据表名称
    private $db_name = 'toEverDown_list';
    //默认配置
    public $defaultOpt = 'a:23:{s:11:"switch_main";s:1:"1";s:17:"radio_displayType";s:1:"2";s:18:"select_defaultType";s:1:"0";s:17:"switch_tips_title";s:1:"1";s:15:"tips_title_text";s:0:"";s:9:"linkCount";s:2:"20";s:18:"switch_button_icon";s:1:"1";s:12:"button_color";s:7:"#5298ff";s:17:"button_text_color";s:7:"#ffffff";s:20:"button_border_radius";s:1:"5";s:18:"reply_border_color";s:7:"#5298ff";s:23:"page_background_color_1";s:0:"";s:23:"page_background_color_2";s:0:"";s:12:"download_des";s:625:"<ul class="help">
<li>1、本站提供的压缩包若无特别说明，解压密码均为<em>woaif.cn</em>。</li>
<li>2、下载后文件若为压缩包格式，请安装7Z软件或者其它压缩软件进行解压。</li>
<li>3、文件比较大的时候，建议使用下载工具进行下载，浏览器下载有时候会自动中断，导致下载错误。</li>
<li>4、资源可能会由于内容问题被和谐，导致下载链接不可用，遇到此问题，请到文章页面进行反馈，我们会及时进行更新的。</li>
<li>5、其他下载问题请自行搜索教程，这里不一一讲解。</li>
</ul>";s:18:"station_master_des";s:393:"<span>本站大部分下载资源收集于网络，只做学习和交流使用，版权归原作者所有，若为付费资源，请在下载后24小时之内自觉删除，若作商业用途，请到原网站购买，由于未及时购买和付费发生的侵权行为，与本站无关。本站发布的内容若侵犯到您的权益，请联系本站删除，我们将及时处理！</span>";s:9:"ad_text_1";s:0:"";s:8:"ad_pic_1";s:0:"";s:18:"ad_pic_bisection_1";s:0:"";s:14:"ad_pic_block_1";s:0:"";s:9:"ad_text_2";s:0:"";s:8:"ad_pic_2";s:0:"";s:18:"ad_pic_bisection_2";s:0:"";s:14:"ad_pic_block_2";s:0:"";}';

    /**
     * 私有构造函数，保证单例
     */
    private function __construct(){
        global $xfdb;
        $this->db = $xfdb;
    }

    /**
     * 单例入口
     */
    public static function getInstance(){
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 视图路径输出
     */
    public function view_path($filename = null, $ext = '.php'){
        if(!empty($filename)){
            $filepath = TOEVER_DOWNPAGE_ROOT."/core/view/{$filename}{$ext}";
            if(file_exists($filepath)){
                return $filepath;
            }
            emMsg('模板文件不存在');
        }
        emMsg('未定义模板');
    }
    /**
     * 数据表名输出
     */
    public function name($name = null, $check = true){
        if(!empty($name)){
            $result = $check ? DB_PREFIX . $name : $name;
            return $result;
        }
        return $name;
    }
    /**
     * 判断是否ajax访问
     */
    public static function isAjax(){
        $SERVER = isset($_SERVER["HTTP_X_REQUESTED_WITH"]) ? $_SERVER["HTTP_X_REQUESTED_WITH"] : '';
        if(isset($SERVER) && strtolower($SERVER) == 'xmlhttprequest'){
            return true;
        }
        return false;
    }
    /**
     * 输出json数据
     */
    public static function json($data = null){
        header('content-type: application/json; charset=UTF-8');
        exit(json_encode($data));
    }
    /**
     * 去除字符串中的空格及换行符
     * @return $string 输出处理后的字符串
     * @return $backslash 是否开启引号自动加反斜杠，默认开启
     */
    public static function stringReset($string = '', $backslash = true){
        if(!empty($string)){
            $string = str_replace(["\n", "\r", "\t", "\s"], '', $string);
            if($backslash){
                $string = addslashes($string);
            }
        }
        return $string;
    }
    /**
     * 传文章数据获取封面图
     */
    public static function getCover($logs = []){
        $imgUrl = '';
        if(!empty($logs)){
            if(!empty($logs['cover'])){
                $imgUrl = ltrim($logs['cover'], '.');
            }else{
                preg_match('|!\[(.*)\]\((.+)\)|i', $logs['content'], $regExp_1);
                preg_match('|<img[^>]+src=\"([^>\"]+)\"?[^>]*>|is', $logs['content'], $regExp_2);
                if(isset($regExp_1[2]) && isset($regExp_2[1])){
                    $place_1 = strpos($logs['content'], $regExp_1[2]);
                    $place_2 = strpos($logs['content'], $regExp_2[1]);
                    if($place_1 < $place_2){
                        $imgUrl = @$regExp_1[2];
                    }else{
                        $imgUrl = @$regExp_2[1];
                    }
                }
                if(isset($regExp_1[2])){
                    $imgUrl = @$regExp_1[2];
                }
                if(isset($regExp_2[1])){
                    $imgUrl = @$regExp_2[1];
                }
            }
        }
        return $imgUrl;
    }

    /**
     * 后台生成二维码图片
     */
    public static function qrcode_base64($url){
        //引入phpqrcode类库
        require_once TOEVER_DOWNPAGE_ROOT.'/core/class/qrcode.class.php';
        $errorCorrectionLevel = 'L'; //容错级别
        $matrixPointSize      = 6; //生成图片大小
        ob_start();
        QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
        $data = ob_get_contents();
        ob_end_clean();

        $imageString = base64_encode($data);
        header("content-type:application/json; charset=utf-8");
        return 'data:image/jpeg;base64,' . $imageString;
    }

    /**
     * 订单号生成
     */
    public static function getOrderId() {
        // 日期和时间
        $date = date('YmdHis');
        // 随机数, 为了确保唯一性，这里可以生成较长的随机数
        $randomNumber = mt_rand(100000, 999999);
        //唯一标识符
        $uniqueIdentifier = uniqid();
        $orderId = $date . $randomNumber . $uniqueIdentifier;
        return $orderId;
    }

    /**
     * 支付宝官方发起支付
     */
    public static function init_pay($order_data = []){
        if (empty($order_data)) {
            return ['code' => 1, 'msg' => '提交信息错误。'];
        }
        //获取参数
        $storage = Storage::getInstance('toEver_downPage');
        $options = unserialize($storage->getValue('options'));
        $config = $options['alipay'];

        /**支付宝当面付 */
        if (empty($config['privatekey']) || empty($config['appid']) || empty($config['publickey'])) {
            return ['code' => 1, 'msg' => '支付宝后台配置无效'];
        }

        // 配置文件
        $params                = new \Yurun\PaySDK\AlipayApp\Params\PublicParams;
        $params->appID         = $config['appid'];
        $params->appPrivateKey = $config['privatekey'];
        $params->appPublicKey  = $config['publickey'];
        // SDK实例化，传入公共配置
        $pay = new \Yurun\PaySDK\AlipayApp\SDK($params);
        // 支付接口
        $request                               = new \Yurun\PaySDK\AlipayApp\FTF\Params\QR\Request;
        $request->notify_url                   = TOEVER_DOWNPAGE_URL . '/core/pay/callback/alipay/notify.php'; // 支付后通知地址
        $request->businessParams->out_trade_no = $order_data['order_id']; // 商户订单号
        $request->businessParams->total_amount = $order_data['order_price']; // 价格
        $request->businessParams->subject      = $order_data['order_name']; // 商品标题

        // 调用接口
        try {
            $data = $pay->execute($request);
        } catch (Exception $e) {
            return ['code' => 1, 'msg' => $e->getMessage()];
            //  var_dump($pay->response->body());
        }

        if (!empty($data['alipay_trade_precreate_response']['qr_code'])) {
            $data['alipay_trade_precreate_response']['url_qrcode'] = self::qrcode_base64($data['alipay_trade_precreate_response']['qr_code']);
            $data['alipay_trade_precreate_response']['msg']        = '处理完成，请扫码支付';
            $data['alipay_trade_precreate_response']['check_sdk'] = 'official_alipay'; //接口查询
            $data['alipay_trade_precreate_response']['order_price'] = $order_data['order_price'];
            $data['alipay_trade_precreate_response']['order_name'] = $order_data['order_name'];
            $data['alipay_trade_precreate_response']['mobile_tips'] = '扫码支付或点此跳转到支付宝APP付款';
            return $data['alipay_trade_precreate_response'];
        } else {
            return ['code' => 1, 'msg' => $pay->getError() . ' ' . $pay->getErrorCode()];
        }
    }

    //后台列表按钮
    public function admin_menu(){
        require_once $this->view_path('admin_menu');
    }

    //下载表单提交模块
    public function admin_form(){
        $action = Input::requestStrVar('action', '');
        $gid = Input::requestNumVar('gid', 0);
        $data = $this->db->get("{$this->db_name}", "*", [
            "gid" => $gid
        ]);
        $storage = Storage::getInstance('toEver_downPage');
        $options = unserialize($storage->getValue('options'));
        require_once $this->view_path('admin_form');
    }

    //框架模板输出
    public function admin_iframe(){
        include $this->view_path('admin_iframe');
    }

    //首页
    public function admin_index(){
        //ajax返回数据
        if($this->isAjax()){
            $page = Input::requestNumVar('page', 1);
            $limit = Input::requestNumVar('limit', 30);
            $page = ($page - 1) * $limit;
            $list_data = $this->db->select("{$this->db_name}", "*", [
                "ORDER" => [
                    "gid" => "DESC"
                ],
                "LIMIT" => [$page, $limit]
            ]);
            $cache_list_data = [];
            $list_data_count = count($list_data);
            foreach($list_data as $key => $val){
                $logData = $this->db->get("blog", "*", [
                    "gid" => $val['gid']
                ]);
                $list_data[$key]['name'] = $logData['title'];
                $list_data[$key]['edit_url'] = 'article.php?action=edit&gid='.$val['gid'];
                $list_data[$key]['log_url'] = Url::log($val['gid']);
            }
            $result = [
                'code' => 0,
                'msg' => 'ok',
                'count' => $list_data_count,
                'data' => $list_data
            ];
            $this->json($result);
        }
        require_once $this->view_path('admin_index');
        exit;
    }

    //配置页
    public function admin_config(){
        $storage = Storage::getInstance('toEver_downPage');
        if($this->isAjax()){
            $options = $_REQUEST['options'];
            $storage->setValue('options', serialize($options));
            Output::ok($options);
        }
        $options = unserialize($storage->getValue('options'));
        require_once $this->view_path('admin_config');
        exit;
    }

    //恢复默认配置
    public function admin_config_default_api(){
        if($this->isAjax()){
            $storage = Storage::getInstance('toEver_downPage');
            $storage->setValue('options', $this->defaultOpt);
            Output::ok();
        }
    }

    //api接口
    public function admin_form_save($logid, $action){
        $id         = Input::requestNumVar('teDown_id');
        $state      = Input::requestStrVar('teDown_state', 'n');
        $type       = Input::requestNumVar('teDown_type');
        $money      = Input::requestNumVar('teDown_money');
        $list       = Input::postStrArray('teDown_list');

        $blog_count = $this->db->count("{$this->db_name}", "*", [
            "gid" => $logid
        ]);
        if($blog_count == 0){
            if($state === 'y'){
                $this->db->insert("{$this->db_name}", [
                    'gid'           => $logid,
                    'state'         => $state,
                    'type'          => $type,
                    'money'         => $money,
                    'list'          => json_encode(array_values($list), JSON_UNESCAPED_UNICODE)
                ]);
            }
        }else{
            $this->db->update("{$this->db_name}", [
                'state'         => $state,
                'type'          => $type,
                'money'         => $money,
                'list'          => json_encode(array_values($list), JSON_UNESCAPED_UNICODE)
            ], [
                'id' => $id
            ]);
        }
    }

    //属性修改接口
    public function admin_modify_state_api(){
        if($this->isAjax()){
            $id = Input::requestNumVar('id');
            $state = Input::requestStrVar('state');
            $update = $this->db->update("{$this->db_name}", [
                'state' => $state
            ], [
                'id' => $id
            ]);
            if($update->rowCount()){
                Output::ok();
            }
            Output::error('修改失败');
        }
    }

    //删除改接口
    public function admin_delete_api(){
        if($this->isAjax()){
            $id = Input::requestNumVar('id');
            $delete = $this->db->delete("{$this->db_name}", [
                'id' => $id
            ]);
            if($delete->rowCount()){
                Output::ok();
            }
            Output::error('删除失败');
        }
    }

    //前台输出
    public function index_view($logData){
        $storage = Storage::getInstance('toEver_downPage');
        $options = unserialize($storage->getValue('options'));
        $downData = $this->db->get("{$this->db_name}", "*", [
            'gid' => $logData['logid'],
            'state' => 'y'
        ]);
        if ((int)$options['radio_displayType'] == 2) {
            $downList = [];
            if (is_array($downList)) {
                $downList = isset($downData['list']) ? json_decode($downData['list'], true) : [];
                foreach ($downList as $key => $val) {
                    $downList[$key]['url'] = '/?plugin='.TOEVER_DOWNPAGE_NAME.'&gid='.$logData['logid'];
                }
                $downData['list'] = json_encode($downList);
            }
        }
        $lock = true;
        if (isset($downData['type'])) {
            switch ($downData['type']) {
                case 0:
                    $lock = false;
                break;
                case 1:
                    if (!empty(UID) && ROLE === ROLE_ADMIN) {
                        $lock = false;
                    } elseif (isset($_COOKIE['toEverDownPage_comment_gid']) && $_COOKIE['toEverDownPage_comment_gid'] == $logData['logid']) {
                        $lock = false;
                    }
                break;
                case 2:
                    if (!empty(UID)){
                        $lock = false;
                    }
                break;
                case 3:
                    if (!empty(UID) && ROLE === ROLE_ADMIN) {
                        $lock = false;
                    } elseif (isset($_COOKIE['pay_num'])) {
                        $row = $this->db->get('toEverDown_order', [
                            'state'
                        ], [
                            'post_id' => $gid,
                            'pay_num' => $_COOKIE['pay_num']
                        ]);
                        if(!empty($row) && $row['state'] == 1){
                            $lock = false;
                        }
                    }
                break;
            }
        }
        if ($options['radio_displayType'] == 2) $lock = false;
        require_once $this->view_path('index_view');
    }

}
//初始化
toEverDownPage::getInstance();