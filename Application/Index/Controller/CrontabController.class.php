<?php
namespace Index\Controller;
use Think\Controller;
class CrontabController extends CommonController{
   //------------------------------
   protected function _initialize(){
	   if(APP_MODE!='cli'){
          return false;
       }
   }
   //------------------------------商品上架
   public function onSale(){     
       $map['status'] = 1;                         //取出所有预售的商品id                                
       $presale_goods_ids = M('Goods')->field('id')->where($map)->select();
       foreach($presale_goods_ids as $k=>$v){
          if($v['onsale_time'] < time()){          //假如当前时间已经大于该商品上架时间
             M('Goods')->where('id='.$v['id'])->save(array('status'=> 2 ));//修改为现货
          }
       }
   }
   //----------------订单取消/cli模式
   public function cancle(){
      //-----------------------
      $map['status'] = C('ORDER_STATUS_VAL.toPay');             //取出新增的订单(未付款)
      $map['add_time'] = array('elt',time()-(3600*24));          //已经超过一日还未支付
      $cancle_order = D('Order')->where($map)->relation('order_goods')->select();
      M()->startTrans();
      foreach($cancle_order as $k=>$v){
        $status = D('Order')->where($map)->save(array('status'=>C('ORDER_STATUS_VAL.cancel'),'cancel_time'=>time()));
        $res = D('GoodsPrivate')->stockUpate($v['goods'],$mode = 'dec');
        if($res === false){
          M()->rollback();
          return false; 
        } 
      }
      M()->commit();
   }
   //----------------订单已收货/cli模式
   public function orderReceived(){
      $model = M('Order');
      $send_order = $model->where('status='.C('ORDER_STATUS_VAL.send'))->select();
      
      foreach($send_order as $k=>$v){
	     $express_abbr = C('EXPRESS_COM_ABBR'.$v['express_com']); //快递公司代号
         $url = 'http://api.kuaidi.com/openapi.html?id='.C('EXPRESS_API_KEY').'&com='.$express_abbr.'&nu='.$v['express_num'].'&show=0&muti=0&order=desc';
         $express_msg_json = file_get_contents($url);
         $express_msg_arr = object_array(json_decode($express_msg_json));
         //--------------
         $data = array();
         if($express_msg_arr['status'] == 6){ //假设已收货，修改订单状态
            $data['status'] = C('ORDER_STATUS_VAL.receive');
            $data['take_time'] = time();
         }
         $data['express_msg'] = $express_msg_json;
         $model->where('id='.$v['id'])->save($data);
	  }
   }
   //----------------订单完成/cli模式
   public function orderFinish(){
      $receive_order = M('Order')->field('id')->where('status='.C('ORDER_STATUS_VAL.receive'))->select();
      foreach($receive_order as $k=>$v){
	     if($v['take_time'] < time()-(3600*24*7)){
	        M('Order')->where('id='.$v['id'])->save(array('status'=> C('ORDER_STATUS_VAL.finish')));
	     }
	  }
   }
   //----------------全站商品数字/今周新品/cli模式
   public function siteInfo(){
       $site_info = array();
       //---------------------------------------------
       $map['on_sale'] = 1;
       $site_info['goods_count'] = M('Goods')->where($map)->count();   //全站商品数
       
       $map['add_time'] = array('GT',time()-(3600*24*7));
       $site_info['goods_week'] = M('Goods')->where($map)->count();   //今周新品商品数
       
       S('site_info',$site_info,3600*24*7);
       //---------------------------------------------
   }
   
   //----------------商品库存警告
   public function stockRateCal(){
     //计算库存比率
     $gp = M('GoodsPrivate');
     $res = $gp->field('gpid,stock,sales')->select();
     foreach($res as $k=>$v){
        $map['gpid'] = $v['gpid'];
        $data['stock_rate'] = (float)($v['stock']/($v['stock']+$v['sales']));
        $gp->where($map)->save($data);
     }
   }
//-------------------------------------------------------------------------
}

?>