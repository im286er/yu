<?php
namespace Admin\Controller;
use Think\Controller;
class PublicMsgController extends CommonController{
   //---------------------------------
   public function index(){  
       if(IS_POST){
             if(I('title'))  $map['title'] = array('like','%'.I('title').'%');
       }
       $this->mapSearch('PublicMsg',$map);
       $this->display();
   }
   //---------------------------------
   public function add() {
      $this->display();
   }
   //---------------------------------
   public function insert() {
      $pm = D('PublicMsg');
      if($pm->create()){      
          $pmid = $pm->add();
          if($pmid){ 
             $this->success('公告新增成功!');
          } else {
             $this->error('公告提交失败!');
          }
      }else{
        $this->error($pm->getError());  
      }
   }
   //---------------------------------
   public function edit() {
      $this->vo =  M('PublicMsg')->find(I('id'));
      $this->display();
   }
   //---------------------------------
   public function update() {
      $pm = D('PublicMsg');
      if($pm->create()){      
          if($pm->save()){ 
             $this->success('公告编辑成功!');
          } else {
             $this->error('公告编辑失败!');
          }
      }else{
        $this->error($pm->getError());  
      }
   }	
//----------------------------------------
}
?>