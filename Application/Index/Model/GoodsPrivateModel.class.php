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
    //--------------------------------��Ʒ������Ϣ
    private function _gpbuyInfo($gpid,$num){
        $gpinf = $this->relation('goods')->field('gpid,gid,attr,attr_match,price,stock,weight')->find($gpid);
        $gpinf['num'] = $num;
        $gpinf['item_price'] = $num * $gpinf['price'];
        $gpinf['item_weight'] = $num * $gpinf['weight'];
        return $gpinf;
    }
    //--------------------------------��Ʒ˽��������Ϣ
    public function gpInfo($gid,$attr){
         $map['gid'] = $gid; 
         if($attr==''){
            $map['default'] = 1;
         }else{
            $map['attr'] = $attr;
         }
         $gp = $this->where($map)->find();
         if(empty($gp)) $gp = $this->where('gid='.$gid.' AND `default`= 1')->find(); //attr����ȷʱ���Ĭ��ֵ
         $gp['attr_img']  = $gp['img'];
         if(strstr($gp['attr'],'-')){
            $attr_arr = explode('-',$gp['attr']);
            $gp['attr_1'] = $attr_arr[0];
            $gp['attr_2'] = $attr_arr[1];
         }
         return $gp;
    }
    //-------------------------------���ﳵ��Ϣ
    public function cgInfo($cart){
      $res = array();
      if(!empty($cart)){
        foreach($cart as $k=>$v){       
           $res[$k] = $this->_gpbuyInfo($k,$v['num']);                           
        }
      }
      return $res; 
    }
    //-------------------------------���������������
    public function quickInfo($gpid,$num){
        $res = array();
        $res['address_id'] = NULL;     //Ĭ�ϵ�ַ
        $res['express_fee'] = NULL;    //Ĭ���˷�
        
        $qi = $this->_gpbuyInfo($gpid,$num);
        $res['goods'][$gpid] = $qi;
        $res['total_weight'] = $qi['express_charge'] == 1 ? $qi['item_weight'] : 0;
        $res['total_goods_price'] = $qi['item_price'];
        $res['send_time'] = $qi['onsale_time'];
        $res['quick'] = 1;
        $default_address_id = addressChoosed(); //�û�Ĭ���ͻ���ַ
        if($default_address_id){
         $res['address_id'] = $default_address_id;
         $res['express_fee'] = calExpressFee($default_address_id,$res['total_weight']);
        }
        $res['order_price'] = floatval($res['total_goods_price']+$res['express_fee']);
        return $res;
    }
    //-------------------------------�����������
    public function settleInfo($gpid_arr,$cart){
      $res = array();
      $res['address_id'] = NULL;  //Ĭ���ͻ���ַ
      $res['express_fee'] = NULL; //Ĭ���˷�
      $total_weight = 0;          //����
      $total_goods_price = 0;     //���˷�
      
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
      $res['send_time'] = max($send_time_arr);   //��Ʒȫ���ϼܾ�������ʱ��
      $default_address_id = addressChoosed();    //�û�Ĭ���ͻ���ַ
      if($default_address_id){
         $res['address_id'] = $default_address_id;
         $res['express_fee'] = calExpressFee($default_address_id,$total_weight);
      }
      $res['total_weight'] = $total_weight;
      $res['total_goods_price'] = $total_goods_price;
      $res['order_price'] = floatval($total_goods_price+$res['express_fee']);
      return $res;
    }
    //-------------------------------�ϲ�����
    /**
     * $gpid_arr              ѡ�е���Ʒ
     * $cart                  ���ﳵ����
     * $express_fee_merge     �Ѹ��˷�
     * $total_weight_merge    ���ն���������
     * $default_address_id    Ĭ�ϵ�ַ
    */
     public function settleInfoMerge($gpid_arr,$cart,$express_fee_merge,$total_weight_merge,$default_address_id){
      $res = array();$res['address_id'] = NULL;$res['express_fee'] = NULL;
      $total_weight = 0;$total_goods_price = 0;
      
      foreach($gpid_arr as $k=>$v){
         if(array_key_exists($v,$cart)){
           $si= $this->_gpbuyInfo($v,$cart[$v]['num']);
           $res['goods'][$v] = $si;
           if($si['express_charge'] == 1){
            $total_weight += $si['item_weight'];      //���ﳵ��Ʒ����
           }
           $total_goods_price += $si['item_price'];   //���ﳵ��Ʒ����Ʒ�۸�
           $send_time_arr[] = $si['onsale_time'];
         }
      }
      $res['send_time'] = max($send_time_arr);        //�����ϲ�������,Ĭ���ͻ�ʱ��
      $res['address_id'] = $default_address_id;       //���ϲ�������Ĭ���ͻ���ַ
      $total_merge_weight = $total_weight + $total_weight_merge;  //�ϲ�������
      $res['express_fee'] = calExpressFee($default_address_id,$total_merge_weight) - $express_fee_merge ; //�ϲ����˷�
      $res['total_weight'] = $total_weight;
      $res['total_goods_price'] = $total_goods_price;
      $res['order_price'] = floatval($total_goods_price + $res['express_fee']);
      return $res;
    }
    //-------------------------------��Ʒ����������仯
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