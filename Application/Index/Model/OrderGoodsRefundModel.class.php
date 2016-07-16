<?php
namespace Index\Model;
use Think\Model\RelationModel;
class OrderGoodsRefundModel extends RelationModel{
   //-------------------------------------------------
   protected $_validate = array(
     array('gpid','require','商品gpid为空!'), 
     array('gid','require','商品gid为空 null!'), 
     array('description','require','描述为空!'), 
     array('type','require','退换货类型为空!'),
     array('order_id','require','订单信息id为空!'),
     array('order_sn','require','订单信息sn为空!'),
   );
   //--------------------------------------------------
   protected $_auto = array ( 
             array('add_time','time',1,'function'),  
             array('uid','current_uid',1,'function'), 
   );
//------------------------------------------
}
?>