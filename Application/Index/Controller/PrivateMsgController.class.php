<?php
namespace Index\Controller;
use Think\Controller;
class PrivateMsgController extends CommonController{
   //-------------------------------------------------
   public function msgRead(){
      $map['rec_uid'] = session('uid');
      $map['id'] = array('IN',I('ids'));
      $data['read'] = 1;
      M('PrivateMsg')->where($map)->save($data);
   }
//----------------------------------------------
}

?>