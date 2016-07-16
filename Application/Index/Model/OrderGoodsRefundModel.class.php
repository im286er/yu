<?php
namespace Index\Model;
use Think\Model\RelationModel;
class OrderGoodsRefundModel extends RelationModel{
   //-------------------------------------------------
   protected $_validate = array(
     array('gpid','require','��ƷgpidΪ��!'), 
     array('gid','require','��ƷgidΪ�� null!'), 
     array('description','require','����Ϊ��!'), 
     array('type','require','�˻�������Ϊ��!'),
     array('order_id','require','������ϢidΪ��!'),
     array('order_sn','require','������ϢsnΪ��!'),
   );
   //--------------------------------------------------
   protected $_auto = array ( 
             array('add_time','time',1,'function'),  
             array('uid','current_uid',1,'function'), 
   );
//------------------------------------------
}
?>