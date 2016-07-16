<?php
namespace Admin\Behaviors;
use Think\Controller;
use Admin\Util\Auth;
class authBehavior extends \Think\Behavior { //行为执行入口
   public function run(&$param) {
        ob_end_clean();    
        if(CONTROLLER_NAME!=C('NO_CHEACK_CONTROLLER')){
            if(session('admin')){
                $uid = session('uid'); 
                if($uid){
                    $auth = new Auth();
                    if($auth->check(CONTROLLER_NAME.'/'.ACTION_NAME,$uid)==0){
                        exit('Authority Deny');
                        return false;
                    } 
                }else{
                    exit('Error');
                    return false;
                }    
            }else{               
                 //echo " <script> window.top.location.href='/Admin/Login/';</script> ";
                 exit('Error');
                 return false;
            }
        }
   }
//------------------------------------------
}
