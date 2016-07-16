<?php
namespace Index\Model;
use Think\Model\RelationModel;
class GoodsPrivateModel extends RelationModel{
    //---------------------------------------------------
    protected $_link = array(
             'goods' => array(
                    'mapping_type'  => self::BELONGS_TO,
                    'class_name'    => 'Goods',
                    'foreign_key'   => 'gid',
                    'mapping_name'  => 'goods',
                    'mapping_fields'=>'attr_des,multi,status,express_charge,onsale_time',
                    'as_fields'=>'attr_des,multi,status,express_charge,onsale_time',
             ),
    );
    //--------------------------------商品购物信息
    private function _gpbuyInfo($gpid,$num){
        $gpinf = $this->relation('goods')->field('gpid,gid,attr,attr_match,price,stock,weight')->find($gpid);
        $gpinf['num'] = $num;
        $gpinf['item_price'] = $num * $gpinf['price'];
        $gpinf['item_weight'] = $num * $gpinf['weight'];
        return $gpinf;
    }
    //--------------------------------商品私有属性信息
    public function gpInfo($gid,$attr){
         $map['gid'] = $gid; 
         if($attr==''){
            $map['default'] = 1;
         }else{
            $map['attr'] = $attr;
         }
         $gp = $this->where($map)->find();
         if(empty($gp)) $gp = $this->where('gid='.$gid.' AND `default`= 1')->find(); //attr不正确时候的默认值
         $gp['attr_img']  = $gp['img'];
         if(strstr($gp['attr'],'-')){
            $attr_arr = explode('-',$gp['attr']);
            $gp['attr_1'] = $attr_arr[0];
            $gp['attr_2'] = $attr_arr[1];
         }
         return $gp;
    }
    //-------------------------------购物车信息
    public function cgInfo($cart){
      $res = array();
      if(!empty($cart)){
        foreach($cart as $k=>$v){       
           $res[$k] = $this->_gpbuyInfo($k,$v['num']);                           
        }
      }
      return $res; 
    }
    //-------------------------------立即购买陈列数据
    public function quickInfo($gpid,$num){
        $res = array();
        $res['address_id'] = NULL;     //默认地址
        $res['express_fee'] = NULL;    //默认运费
        
        $qi = $this->_gpbuyInfo($gpid,$num);
        $res['goods'][$gpid] = $qi;
        $res['total_weight'] = $qi['express_charge'] == 1 ? $qi['item_weight'] : 0;
        $res['total_goods_price'] = $qi['item_price'];
        $res['send_time'] = $qi['onsale_time'];
        $res['quick'] = 1;
        $default_address_id = addressChoosed(); //用户默认送货地址
        if($default_address_id){
         $res['address_id'] = $default_address_id;
         $res['express_fee'] = calExpressFee($default_address_id,$res['total_weight']);
        }
        $res['order_price'] = floatval($res['total_goods_price']+$res['express_fee']);
        return $res;
    }
    //-------------------------------结算陈列数据
    public function settleInfo($gpid_arr,$cart){
      $res = array();
      $res['address_id'] = NULL;  //默认送货地址
      $res['express_fee'] = NULL; //默认运费
      $total_weight = 0;          //总重
      $total_goods_price = 0;     //总运费
      
      foreach($gpid_arr as $k=>$v){
         if(array_key_exists($v,$cart)){
           $si= $this->_gpbuyInfo($v,$cart[$v]['num']);
           $res['goods'][$v] = $si;
           if($si['express_charge'] == 1){
            $total_weight += $si['item_weight'];
           }
           $total_goods_price += $si['item_price'];
           $send_time_arr[] = $si['onsale_time'];
         }
      }
      $res['send_time'] = max($send_time_arr);   //商品全部上架就绪发货时间
      $default_address_id = addressChoosed();    //用户默认送货地址
      if($default_address_id){
         $res['address_id'] = $default_address_id;
         $res['express_fee'] = calExpressFee($default_address_id,$total_weight);
      }
      $res['total_weight'] = $total_weight;
      $res['total_goods_price'] = $total_goods_price;
      $res['order_price'] = floatval($total_goods_price+$res['express_fee']);
      return $res;
    }
    //-------------------------------合并结算
    /**
     * $gpid_arr              选中的商品
     * $cart                  购物车数据
     * $express_fee_merge     已付运费
     * $total_weight_merge    参照订单的总重
     * $default_address_id    默认地址
    */
     public function settleInfoMerge($gpid_arr,$cart,$express_fee_merge,$total_weight_merge,$default_address_id){
      $res = array();$res['address_id'] = NULL;$res['express_fee'] = NULL;
      $total_weight = 0;$total_goods_price = 0;
      
      foreach($gpid_arr as $k=>$v){
         if(array_key_exists($v,$cart)){
           $si= $this->_gpbuyInfo($v,$cart[$v]['num']);
           $res['goods'][$v] = $si;
           if($si['express_charge'] == 1){
            $total_weight += $si['item_weight'];      //购物车商品总重
           }
           $total_goods_price += $si['item_price'];   //购物车商品总商品价格
           $send_time_arr[] = $si['onsale_time'];
         }
      }
      $res['send_time'] = max($send_time_arr);        //主动合并订单方,默认送货时间
      $res['address_id'] = $default_address_id;       //被合并订单的默认送货地址
      $total_merge_weight = $total_weight + $total_weight_merge;  //合并后总重
      $res['express_fee'] = calExpressFee($default_address_id,$total_merge_weight) - $express_fee_merge ; //合并后运费
      $res['total_weight'] = $total_weight;
      $res['total_goods_price'] = $total_goods_price;
      $res['order_price'] = floatval($total_goods_price + $res['express_fee']);
      return $res;
    }
    //-------------------------------商品库存与销量变化
    public function stockUpate($order_goods,$mode='dec'){ 
          $map = array();
          if(!empty($order_goods)){
                foreach($order_goods as $k=>$v){
                    $map['gpid'] = $v['gpid'];
                    $map['gid'] = $v['gid'];
                    if($mode=='dec'){
                        $dec = $this->where($map)->setDec('stock',$v['num']);
                        $inc = $this->where($map)->setInc('sales',$v['num']);
                    }else{
                        $dec = $this->where($map)->setDec('sales',$v['num']);
                        $inc = $this->where($map)->setInc('stock',$v['num']);
                    }
                    if(!$dec || !$inc) return false;
                    $map = array();
                }
                return true;
          }else{
                return false;
          }
    }
//----------------
}

?>