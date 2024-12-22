<?php

header('Content-type:text/html; Charset=utf-8');

ob_start();
require_once dirname(__FILE__) . '/../../../../../../../init.php';
require_once TOEVER_DOWNPAGE_ROOT . '/core/pay/class/alipay-check.php';
ob_end_clean();

if (empty($_POST['sign'])) {
    echo '非法请求';
    exit();
}

$storage = Storage::getInstance('toEver_downPage');
$options = unserialize($storage->getValue('options'));
$config = $options['alipay'];

$aliPay = new AlipayServiceCheck($config['publickey']);
//验证签名
$rsaCheck = $aliPay->rsaCheck($_POST);
if ($rsaCheck && $_POST['trade_status'] == 'TRADE_SUCCESS') {
    // 通知验证成功，可以通过POST参数来获取支付宝回传的参数
    $pay = array(
        'order_num' => $_POST['out_trade_no'],
        'pay_type'  => 'official_alipay',
        'pay_price' => $_POST['total_amount'],
        'pay_num'   => $_POST['trade_no'],
        'pay_time'  => time(),
        'state'     => 1,
    );

    $order_data = $xfdb->get('toEverDown_order', '*', [
        'order_num' => $_POST['out_trade_no']
    ]);

    if(!empty($order_data) && $order_data['state'] != 1){
        // 更新订单状态
        $order_update = $xfdb->update('toEverDown_order', $pay, [
            'order_num' => $pay['pay_num']
        ]);
    }

    /**返回不在发送异步通知 */
    echo "success";
    exit();
} else {
    // 通知验证失败
    file_put_contents(__DIR__ . '/notify_result.txt', '//AlipayServiceCheck:' . $rsaCheck . PHP_EOL . '$_POST:' . json_encode($_POST));
}
/**返回不在发送异步通知 */
echo "error";
exit();
