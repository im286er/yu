<?php
namespace Index\Controller;
use Think\Controller;
class PageController extends CommonController{
   //-----------------------------------
   public function index(){
       $map['sign'] = I('get.sign')==''?'gwxz':I('get.sign');
       $this->assign('vo',M('Page')->where($map)->field('content')->find());
       $this->display();
   }
//----------------------------------------------
}

?>