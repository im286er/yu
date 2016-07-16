<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$
namespace Index\Util;
class Alipay{
    //加载支付宝支付类所需函数
	public function __construct(){
		require_once 'Alipay/alipay_core.function.php';
		require_once 'Alipay/alipay_md5.function.php';
        require_once 'Alipay/alipay_submit.class.php';
        require_once 'Alipay/alipay_notify.class.php'; 
	}
    //提交比支付宝,进行支付
    public function doPay($out_trade_no,$total_fee){
        $alipay_config['seller_email']	= C('Alipay.seller_email');
        $alipay_config['partner']		= C('Alipay.partner');
        $alipay_config['key']			= C('Alipay.key');
        $alipay_config['cacert']        = C('Alipay.cacert');
        
        $alipay_config['transport']    = 'http';
        $alipay_config['sign_type']    = strtoupper('MD5');
        $alipay_config['input_charset']= strtolower('utf-8');
        $payment_type = "1";
        
        $notify_url = C('Alipay.notify_url');
        $return_url = C('Alipay.return_url');
        
        $subject = '御咖塘-'.$out_trade_no.'号订单';  //订单名称
        $body = '广州御咖塘商品购买支付';             //订单描述
        
        $anti_phishing_key = "";                      //防钓鱼时间戳
        $exter_invoke_ip = get_client_ip();           //客户端的IP地址

        $parameter = array(
    		"service" => "create_direct_pay_by_user",
    		"partner" => trim($alipay_config['partner']),
    		"seller_email" => trim($alipay_config['seller_email']),
    		"payment_type"	=> $payment_type,
    		"notify_url"	=> $notify_url,
    		"return_url"	=> $return_url,
    		"out_trade_no"	=> $out_trade_no,         //订单号
    		"subject"	=> $subject,
    		"total_fee"	=> $total_fee,                //订单总价
    		"body"	=> $body,
    		"show_url"	=> $show_url,
    		"anti_phishing_key"	=> $anti_phishing_key,
    		"exter_invoke_ip"	=> $exter_invoke_ip,
    		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );

        //建立请求
       $alipaySubmit = new \AlipaySubmit($alipay_config);
       $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
       echo $html_text;   
    }
    //通知ykt已支付成功POST
    public function verifyPaySuccess(){
           $alipayNotify = new \AlipayNotify(C('Alipay'));
           $verify_result = $alipayNotify->verifyNotify();
           if(!$verify_result) {//验证成功
                file_put_contents('log/payError.txt','Not Alipay Return'.time()); 
                exit('非支付宝回送方支付信息');
           }else{
                if($_POST['trade_status'] == 'TRADE_SUCCESS') {
                    $res['out_trade_no'] = $_POST['out_trade_no'];   //ykt订单号
                    $res['trade_no'] = $_POST['trade_no'];           //支付宝订单号
                    $res['total_fee'] = $_POST['total_fee'];         //订单总金额
                    $res['gmt_payment'] = $_POST['gmt_payment'];     //支付时间
                    return $res;
                }else{
                   file_put_contents('log/payError.txt','PAY FAIL'.time()); 
                   exit('支付失败');
                }
          }
           
    }
    //通知ykt已支付成功GET
    public function verifyPayReturn(){
           $alipayNotify = new \AlipayNotify(C('Alipay'));
           $verify_result = $alipayNotify->verifyReturn();
           if(!$verify_result){
               file_put_contents('log/returnError.txt','Not Alipay getReturn'.time()); 
               exit('非支付宝回送方支付信息');
           }else{
               if($_GET['trade_status'] == 'TRADE_SUCCESS'){
                    $res['out_trade_no'] = $_GET['out_trade_no'];   //ykt订单号
                    $res['trade_no'] = $_GET['trade_no'];           //支付宝订单号
                    return $res;
               }else{
                 file_put_contents('log/returnError.txt','PAY getFAIL'.time()); 
                 exit('非支付宝回送方支付信息');
               } 
           }  
    }
//----------------------------------------
}

?>