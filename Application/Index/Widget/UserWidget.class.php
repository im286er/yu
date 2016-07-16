<?php
namespace Index\Widget;
use Think\Controller;
class UserWidget extends Controller{
    //------------------------------------------
	public function card($uid){ 
       $this->assign('vo',M('User')->find($uid));
       $this->display('Widget:User:card');
	}
//------------------------------------------
}
?>