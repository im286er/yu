<?php
namespace Index\Model;
use Think\Model\RelationModel;
class OrderGoodsModel extends RelationModel{
   //-------------------------------------------------
   public function insert($order_id,$goods){              //添加订单商品记录
      foreach($goods as $k=>$v){
        $dataList[] = array('order_id'=>$order_id,'gid'=>$v['gid'],'gpid'=>$v['gpid'],'num'=>$v['num'],'uid'=>session('uid'));
      } 
     return $this->addAll($dataList);
   }
//------------------------------------------
}
?>