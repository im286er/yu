<?php
namespace Index\Model;
use Think\Model\RelationModel;
class OrderGoodsModel extends RelationModel{
   //-------------------------------------------------
   public function insert($order_id,$goods){              //��Ӷ�����Ʒ��¼
      foreach($goods as $k=>$v){
        $dataList[] = array('order_id'=>$order_id,'gid'=>$v['gid'],'gpid'=>$v['gpid'],'num'=>$v['num'],'uid'=>session('uid'));
      } 
     return $this->addAll($dataList);
   }
//------------------------------------------
}
?>