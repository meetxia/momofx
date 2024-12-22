<?php
!defined('EMLOG_ROOT') && exit();
$pay_type = Input::getStrVar('pay_type');

switch ($pay_type) {
    case 'create_order':
        if(toEverDownPage::isAjax()){
            $storage = Storage::getInstance('toEver_downPage');
            $options = unserialize($storage->getValue('options'));
            $config = $options['alipay'];
            
            if (empty($config['privatekey']) || empty($config['appid']) || empty($config['publickey'])) {
                Output::error('支付宝后台配置无效'); 
            }
            
            $input = filter_input_array(INPUT_GET);
            $order = [
                'post_id' => $input['post_id'],
                'order_num' => toEverDownPage::getOrderId(),
                'order_price' => $input['order_price'],
                'create_time' => time(),
                'pay_type' => 'official_alipay',
                'state' => 0,
                'ip_address' => getIp(),
                'password' => $input['password']
            ];
            $create_order = $xfdb->insert('toEverDown_order', $order);
            if($create_order){
                $orderData = $xfdb->get('toEverDown_order', '*', [
                    'id' => $xfdb->id()
                ]);
                Output::ok($orderData);
            }
            Output::error('订单创建失败'); 
        }
    break;
    case 'alipay':
        if(toEverDownPage::isAjax()){
            $input = filter_input_array(INPUT_GET);
            $res = toEverDownPage::init_pay($input);
            if($res['code'] != 1){
                Output::ok($res);
            }
            Output::error($res['msg']);
        }
    break;
    case 'order_restore':
        if(toEverDownPage::isAjax()){
            $input = filter_input_array(INPUT_GET);
            $data = $xfdb->get('toEverDown_order', '*', [
                'post_id' => $input['post_id'],
                'state' => 1,
                'OR' => [
                    'order_num' => $input['password'],
                    'pay_num' => $input['password'],
                    'password' => $input['password'],
                ]
            ]);
            if(!empty($data)){
                setcookie("pay_num", $data['pay_num']);
                Output::ok();
            }
            Output::error('未查询到订单，请重新输入');
        }
    break;
    case 'check_pay':
        if(toEverDownPage::isAjax()){
            $order_num = Input::getStrVar('order_num');
            if(empty($order_num)){
                Output::error('还未生成订单');
            }
            /**根据订单号查询订单 */
            $order_check = $xfdb->get('toEverDown_order', '*', [
                'order_num' => $order_num
            ]);
    
            if(!empty($order_check) && $order_check['state'] != 1 && time() > $order_check['create_time'] + 6){
                switch($order_check['pay_type']){
                    case 'official_alipay':
                        //支付宝当面付
                        $storage = Storage::getInstance('toEver_downPage');
                        $options = unserialize($storage->getValue('options'));
                        $config = $options['alipay'];
                        if (empty($config['privatekey']) || empty($config['appid']) || empty($config['publickey'])) {
                            break;
                        }
    
                        $params                = new \Yurun\PaySDK\AlipayApp\Params\PublicParams;
                        $params->appID         = $config['appid'];
                        $params->appPrivateKey = $config['privatekey'];
                        $params->appPublicKey  = $config['publickey'];
    
                        // SDK实例化，传入公共配置
                        $sdk                                   = new \Yurun\PaySDK\AlipayApp\SDK($params);
                        $request                               = new \Yurun\PaySDK\AlipayApp\Params\Query\Request;
                        $request->businessParams->out_trade_no = $order_num; // 订单支付时传入的商户订单号,和支付宝交易号不能同时为空。
    
                        try {
                            $result = (array) $sdk->execute($request);
                            $result = !empty($result['alipay_trade_query_response']) ? $result['alipay_trade_query_response'] : '';
    
                            //查询到已经支付
                            if (!empty($result['trade_status']) && $result['trade_status'] === 'TRADE_SUCCESS') {
                                $pay_order_data = array(
                                    'order_num' => $result['out_trade_no'],
                                    'pay_type'  => 'official_alipay',
                                    'pay_price' => $result['total_amount'],
                                    'pay_num'   => $result['trade_no'],
                                    'pay_time'  => time(),
                                    'state'     => 1,
                                );
    
                                // 更新订单状态
                                $order_update = $xfdb->update('toEverDown_order', $pay_order_data, [
                                    'order_num' => $order_num
                                ]);
    
                                setcookie("pay_num", $result['trade_no']);
                                Output::ok();
                            }else{
                                Output::error('还未检测到订单');
                            }
                        } catch (Exception $e) {
                            Output::error($e->getMessage());
                        }
                    break;
                }
            }else{
                Output::error('还未检测到订单');
            }
        }
    break;
}