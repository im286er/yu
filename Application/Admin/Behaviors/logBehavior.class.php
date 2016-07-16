<?php
namespace Admin\Behaviors;
use Think\Controller;
use Common\Util\Auth;
class logBehavior extends \Think\Behavior { //行为执行入口
   
   public function run(&$param) {
     if(session('uid')!=1){
         $log = M('ActionLog');
         $data['time'] = time();
         $data['uid'] = session('uid');
         $data['action'] = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
         $log->add($data);

     }   
   }
//------------------------------------------
}
