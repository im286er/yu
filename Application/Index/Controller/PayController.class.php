<?php
namespace Index\Controller;
use Think\Controller;
use Index\Util\Alipay;
class PayController extends CommonController{
   //------------------------------
   protected function _initialize(){
	   if(!isLogin()){
          $this->yktError('登录超时,请重新登录','/Index/Login/index/');
       }
   }   
   //---------------
   public function test(){
      $alipay = new Alipay();
      $alipay->doPay(time(),0.01);
   } 
   //-----------提交支付------------------- 
   public function submit(){  
     $order = D('Order');
     if (!$order->create()){  
           $this->yktError($order->getError());return false;
     }else{
        $map['id'] = I('order_id');
        $map['uid'] = session('uid');
        $map['order_sn'] = I('order_sn');
        $map['status'] = C('ORDER_STATUS_VAL.toPay');
        $total_fee =  M('Order')->where($map)->getField('order_price');
        if($total_fee){
            $alipay = new Alipay();
            $alipay->doPay(I('order_sn'),$total_fee);
        }else{
           $this->yktError('订单异常,请重新添加商品','/Index/Sale/index'); return false; 
        }
     }
   } 
   //-----------支付成功异步数据获取，post------------------- 
   public function notifyUrl(){
     if(IS_POST){
        $alipay = new Alipay();
        $res =  $alipay->verifyPaySuccess();
        if(!empty($res) && is_array($res)){                  //支付成功,改变订单状态  
                $order_sn = $res['out_trade_no'];            //支付成功订单号
                $pay_time = strtotime($res['gmt_payment']);  //支付成功时间
                D('Order')->payUpdate($order_sn,$pay_time);  //更新订单已支付信息
        }else{
             file_put_contents('post_error.txt',json_encode($res));
            //记录支付不成功 
        }
     }
   } 
   //-----------支付成功同步数据获取，get------------------- 
   public function returnUrl(){
     if(IS_GET){
        $alipay = new Alipay();
        $res =  $alipay->verifyPayReturn();
        if(!empty($res) && is_array($res)){   //支付成功,改变订单状态
            $link[0]['link'] =  U('Index/Sale/index');
            $link[0]['text'] = '继续购买';
            $link[1]['link'] =  U('Index/Home/order');
            $link[1]['text'] = '我的订单';
            $this->yktSuccess('订单已支付成功!',$jumpUrl,$sec,$link); 
        }else{
           $this->yktError('订单异常,请重新添加商品',U('Index/Sale/index')); return false; 
        }
     }
   }  
//--------------------------------------------------------------------------------------------------------------
}

?>