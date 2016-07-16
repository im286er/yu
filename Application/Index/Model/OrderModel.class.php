<?php
namespace Index\Model;
use Think\Model\RelationModel;
class OrderModel extends RelationModel{
    //---------------------------------------------------
    protected $_link = array(
          'order_goods' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'OrderGoods',
            'mapping_fields'=>'gid,gpid,num',
            'foreign_key'   => 'order_id',
            'mapping_name'  => 'order_goods',
          ),
    );
    //新增订单
    public function insert($order_info){
        $data['add_time'] = time();
        $data['order_sn'] = substr(date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8).rand(0,99),2);
        $data['uid'] = session('uid');
        $data['address_id']   = $order_info['address_id'];
        $data['express_fee']  = $order_info['express_fee'];
        $data['goods_price']  = $order_info['total_goods_price'];
        $data['total_weight'] = $order_info['total_weight'];
        $data['order_price']  = $order_info['order_price'];
        $data['pid']          = $order_info['pid'] ;
        $data['send_time']    = $order_info['send_time'];
        $msg['order_sn'] = $data['order_sn'];
        $msg['order_id'] = $this->add($data);
        return $msg;
    }   
    //验证订单
    public function validate($map,$passKey){
         $res = array();
         if($passKey=='' || strlen($passKey)!=32){
            $res['msg'] = '订单信息失效-nn';  
            return $res;
         }
         $order_inf = $this->where($map)->relation('order_goods')->find();
         if(empty($order_inf)){
            $res['msg'] = '没有订单信息';
            return $res;
         }
         if($passKey != cryptKey($order_inf['order_sn'])){
            $res['msg'] = '订单信息失效-mm';//订单号没有通过加密验证
            return $res;
         }
         $res['order_inf'] = $order_inf;
         return $res;
    } 
    //支付后更新信息
    public function payUpdate($order_sn,$pay_time){
       $order_inf = $this->where("order_sn = '".$order_sn."'")->find();
       $pay_send_time = $pay_time + (3600*24);            //支付完一日后
       if( $pay_send_time > $order_inf['send_time'] ){  //假设支付时间一日后大于应发时间,更新应发时间
           $new_send_time = $pay_send_time;             //更新发货时间
       }else{
           $new_send_time = $order_inf['send_time'];
       }
       if($order_inf['pid']){                           //假设有父订单
          $p_order_send_time = $this->where('id ='.$order_inf['pid'])->getField('send_time');   //父订单发货时间
          if($new_send_time > $p_order_send_time){      //假如子订单大于父订单发货时间
              $this->where('id ='.$order_inf['pid'])->save(array('send_time'=>$new_send_time)); //更新父订单发货时
          }else{
              $new_send_time = $p_order_send_time;
          } 
       } 
       $data['send_time'] = $new_send_time;
       $data['alipay_serial'] = $res['trade_no']; 
       $data['order_price_pay'] = $res['total_fee'];
       $data['pay_time'] = $pay_time;
       $data['status'] = C('ORDER_STATUS_VAL.hasPay');
       $this->where("order_sn = '".$order_sn."'")->save($data);  
    }
//------------------------------------------
}
?>