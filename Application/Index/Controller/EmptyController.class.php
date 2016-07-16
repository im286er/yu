<?php
namespace Index\Controller;
use Think\Controller;
class EmptyController extends CommonController{
    //------------------------------
    public function index(){
       $this->display('include:404');
    }
//----------------------------------------------
}

?>