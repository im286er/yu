<?php
namespace Index\Controller;
use Think\Controller;
use Index\Util\CartSession;
class OrderController extends CommonController{
   //------------------------------
   protected function _initialize(){
	   if(!isLogin()){
          $this->yktError('登录超时,请重新登录',U('Index/Login/index'));
          return false;
       }
   }
   //---------------检测,返回预订单号信息
   private function _getTempOrder($tcId){
      return S('tcId_'.$tcId);
   }
   //---------------生成订单号信息
   private function _saveTempOrder($order_info){
         $order_info['uid'] = session('uid');
         $tcId = md5(time().rand(1,9999999));
         $key = 'tcId_'.$tcId;             
         S($key,$order_info,60*30);
         return $tcId; 
   }
   //---------------生成订单号信息
   private function _setTempOrder($order_info,$tcId){
         $order_info['uid'] = session('uid');
         $key = 'tcId_'.$tcId;             
         S($key,$order_info,60*30);
         return $tcId; 
   }
   //----------------立即购买订单商品数据
   private function _quickOrder(){
        $res = array();
        $gid = I('gid/d');$gpid = I('gpid/d');$num = I('num/d');
        if(!goodsSaleAllow($gid)){
            return false;
        }else{
            $price = gpidToField($gpid,$gid,'price');  //判断gid,gpid一致性同时，获取商品价格
            if($gid > 0 && $gpid > 0 && $num > 0 && $price > 0){
                $res = D('GoodsPrivate')->quickInfo($gpid,$num);
            } 
            return $res;
        }
   }
   //----------------正常结算订单商品数据
   private function _settleOrder(){
        $res = array();
        $gpid_arr = I('gpid/a');   //确定购买商品gpid数组
        $cart = session('_yktsc_');//购物车原始数据
        if(!empty($gpid_arr) && !empty($cart)){
           $res = D('GoodsPrivate')->settleInfo($gpid_arr,$cart); 
        }else{
           $res = false; 
        }
        return $res;
   }
   //---------------生成预订单号,保存订单临时信息
   public function genernate(){
     if(!IS_POST) $this->yktError('非法访问!');
     $order_info = I('quick/d') == 1 ? $this->_quickOrder():$this->_settleOrder();
     $tcId = $this->_saveTempOrder($order_info);
     if(is_array($order_info) && !empty($order_info) && $tcId){
        redirect(U('Index/Order/info',array('tcId'=>$tcId)));
     }else{
        return $this->yktError('订单信息已失效!');
     }            
   }
   //---------------订单临时信息陈列
   public function info(){     
        $tcId = I('get.tcId/s');
        if($tcId){
            $order_info = $this->_getTempOrder($tcId);
            if(empty($order_info)){
             $this->yktError('订单信息已失效-t!');
             return false;
            }
            if($order_info['uid']!=session('uid')){
              $this->yktError('订单用户错误-u!');
              return false;
            }
            $this->assign('order_info',$order_info);
            $this->assign('address',D('Address')->setting()); 
            $this->display(); 
        }else{
           $this->yktError('不存在订单信息!');  
           return false;
        }
   }
   //---------------计算运费
   public function getExpressFee(){
     if(IS_AJAX){
         $address_id = I('address_id/d');
         $tcId = I('tcId/s'); 
         if($address_id && $tcId){
            $order_info = $this->_getTempOrder($tcId);
            $order_info['express_fee'] =  calExpressFee($address_id,$order_info['total_weight']);
            $order_info['address_id'] = $address_id;
            $order_info['order_price'] = (float)$order_info['total_goods_price'] + (float)$order_info['express_fee'];
            $this->_setTempOrder($order_info,$tcId);
            $res['code'] = 1;
            $res['express_fee'] = $order_info['express_fee'];
            $res['order_price'] = $order_info['order_price'];
         }else{
            $res['code'] = 0;
            $res['msg'] = '参数错误!'; 
         } 
         $this->ajaxReturn($res);
     }
   }
   //---------------清空运费
   public function clearExpressFee(){
     if(IS_AJAX){
         $tcId = I('tcId/s'); 
         $order_info = $this->_getTempOrder($tcId);
         $order_info['express_fee'] =  NULL;
         $order_info['address_id'] = NULL;
         $order_info['order_price'] = $order_info['total_goods_price'];
         $this->_setTempOrder($order_info,$tcId);
         $res['code'] = 1;
         $res['express_fee'] = NULL;
         $res['order_price'] = $order_info['order_price'];
         $this->ajaxReturn($res);
     }
   }
   //---------------提交订单
    public function submit(){
        if (!M()->autoCheckToken($_POST)){
            $res['code'] = 0;
            $res['msg'] = '非法提交数据';
            $this->ajaxReturn($res);
            return false;
        }
        $tcId = I('tcId/s'); 
        $order_info = $this->_getTempOrder($tcId);
        //检查预订单地址
        if(I('address_id')!=$order_info['address_id']){
            $res['code'] = 0;
            $res['msg'] = '缺少订单地址';
            $this->ajaxReturn($res);
            return false;
        }
        //检查预订单运费
        if(is_null($order_info['express_fee'])){
            $res['code'] = 0;
            $res['msg'] = '缺少订单运费';
            $this->ajaxReturn($res);
            return false;  
        }
        //实时检查检查商品库存,事务
        M()->startTrans();
        $order_msg = D('Order')->insert($order_info); 
        $order_id = $order_msg['order_id'];     //新增订单ID
        $order_sn = $order_msg['order_sn'];     //新增订单编号
        //----------------------------------------------------- 
        if(!$order_id){
            $res['code'] = 0;
            $res['msg'] = '添加订单出错';
            M()->rollback();
            $this->ajaxReturn($res);
            return false; 
        }
        //----------------------------------------------------- 
        $order_goods_count = D('OrderGoods')->insert($order_id,$order_info['goods']);  //增加订单商品
        if(count($order_info['goods'])!=$order_goods_count){
            $res['code'] = 0;
            $res['msg'] = '订单商品出错';
            M()->rollback();
            $this->ajaxReturn($res);
            return false; 
        }
        $dec_flag  = D('GoodsPrivate')->stockUpate($order_info['goods'],$mode = 'dec');//减少库存
        if(!$dec_flag){
            $res['code'] = 0;
            $res['msg'] = '商品数量出错或库存不足!';
            M()->rollback();
            $this->ajaxReturn($res);
            return false; 
        }      
        M()->commit();
        if(empty($order_info['quick'])) session('_yktsc_',NULL); //不是立即购买的话,清空购物车
        S($tcId,NULL);                                           //清空预订单
        $res['code'] = 1;
        $res['jumpUrl'] = U('Index/Order/confirm',array('orderSn'=>$order_sn,'passKey'=>cryptKey($order_sn)));  
        $this->ajaxReturn($res);
        return false;
   }       
   //----------------订单确认
   public function confirm(){
      $order_sn = I('get.orderSn/s'); $passKey = I('get.passKey/s');
      $map['uid'] = session('uid');
      $map['status'] = C('ORDER_STATUS_VAL.toPay');
      $map['order_sn'] = $order_sn;
      $res = D('Order')->validate($map,$passKey);
      if($res['order_inf']){
        $this->assign('order_inf',$res['order_inf']);                        
        $this->display();
      }else{
        return $this->yktError($res['msg']);
      }  
   }
   //----------------陈列可合并订单
   public function merge(){
      if(empty(I('gpid/a'))) $this->yktError('未选中待合并商品');
      $map['uid'] = session('uid');
      $map['status']  = C('ORDER_STATUS_VAL.hasPay');
      $map['pid'] = 0;
      $merge_list = D('Order')->where($map)->relation('order_goods')->select();
      $this->assign('gpid_arr',I('gpid/a'));
      $this->assign('merge_list',$merge_list);
      $this->display();   
   }
   //----------------合并订单
   public function doMerge(){
        $gpid_arr = I('gpid/a');       //所选商品gpid数组
        $orderId = I('orderId');       //被合并订单id
        
        $map['uid'] = session('uid');
        $map['id'] = $orderId;
        $map['status']  = C('ORDER_STATUS_VAL.hasPay');
        $merge_order = M('Order')->where($map)->find(); //被合并订单信息
        $cart = session('_yktsc_');  //购物车原始数据
        
        if(empty($gpid_arr) || empty($merge_order) || empty($cart)){
            $this->yktError('合并订单信息缺失');
            return false;
        } 
        
        //被合并订单信息
        $express_fee_merge  = $merge_order['express_fee'];
        $total_weight_merge = $merge_order['total_weight'];
        $address_id         = $merge_order['address_id'];
        //新订单信息
        $order_info = D('GoodsPrivate')->settleInfoMerge($gpid_arr,$cart,$express_fee_merge,$total_weight_merge,$address_id);
        $order_info['pid'] = $merge_order['id']; //父订单id
        //---------------------------
        M()->startTrans();
        $order_msg = D('Order')->insert($order_info); 
        $order_id = $order_msg['order_id'];     //新增订单ID
        $order_sn = $order_msg['order_sn'];     //新增订单编号
        if(!$order_id){
            M()->rollback();
            return $this->yktError('添加订单出错!');
        }
        //----------------------------------------------------- 
        $order_goods_count = D('OrderGoods')->insert($order_id,$order_info['goods']); 
        if(count($order_info['goods']) != $order_goods_count){
            return $this->yktError('订单商品出错!');
        }
        $dec_flag  = D('GoodsPrivate')->stockUpate($order_info['goods'],$mode = 'dec');//减少库存
        if(!$dec_flag){
            M()->rollback();
            return $this->yktError('商品数量出错或库存不足!');
        }
        M()->commit();
        session('_yktsc_',NULL); //清空购物车
        redirect(U('Index/Order/confirm',array('orderSn'=>$order_sn,'passKey'=>cryptKey($order_sn))));  
       
   }
   //----------------取消订单
   public function cancel(){
      $order_sn = I('get.orderSn/s'); $passKey = I('get.passKey/s');
      $map['uid'] = session('uid');
      $map['status'] = C('ORDER_STATUS_VAL.toPay');
      $map['order_sn'] = $order_sn;
      $model = D('Order');
      $res = $model->validate($map,$passKey);
      if($res['order_inf']){
         $order_inf = $res['order_inf'];
         M()->startTrans();
         $status = $model->where($map)->save(array('status'=>C('ORDER_STATUS_VAL.cancel'),'cancel_time'=>time()));
         $dec_flag = D('GoodsPrivate')->stockUpate($order_inf['order_goods'],$mode = 'inc');
         if($dec_flag && $status){
            M()->commit();
            return $this->yktSuccess('订单取消成功!',U('Index/Home/order'));
         }else{
            M()->rollback();
            return $this->yktError('库存修改失败!');
         }
      }else{
        M()->rollback();
        return $this->yktError('合并订单失败!');
      }
   }
//-------------------------------------------------------------------------
}
?>