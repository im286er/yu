<?php
namespace Index\Controller;
use Think\Controller;
class PublicMsgController extends CommonController{
   //-----------------------------------
   public function item(){
      $public_msg_id = I('get.id/d');
      if($public_msg_id < 0) return false;
      $this->assign('vo',M('PublicMsg')->find($public_msg_id));
      $this->display();
   }
//----------------------------------------------
}

?>