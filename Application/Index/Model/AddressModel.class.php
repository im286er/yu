<?php
namespace Index\Model;
use Think\Model\RelationModel;
class AddressModel extends RelationModel{   
   //----------------------  
   protected $_validate = array(
     array('realname','require','收货人必需!'), 
     array('province','require','首地址必要!'), 
     array('street','require','详细地址必要!'),
     array('mobile','require','收货人电话必填!'),
   ); 
   //----------------------
   protected $_auto = array ( 
     array('uid','current_uid',3,'function'),
   );
   //------------------------
   public function setting(){
      $map['uid'] = session('uid');
      return $this->where($map)->order('`default` desc')->limit(5)->select();
   }
   //------------------------
   public function current_id(){
      $map['uid'] = session('uid');
      $map['default'] = 1;
      return $this->where($map)->getField('id');
   }
//----------------------------------
}

?>