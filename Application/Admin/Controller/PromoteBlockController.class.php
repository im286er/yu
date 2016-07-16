<?php
namespace Admin\Controller;  //命名空间
use Think\Controller;        //基类
class PromoteBlockController extends CommonController {
   public function index(){
      $this->assign('list',D('PromoteBlock')->getBlockList());
      $this->display();
   }
   /*----------------------------------------------------------------------*/
   public function add(){
      $this->assign('block',D('PromoteBlock')->getBlockList());
      $this->display();
   }
   /*----------------------------------------------------------------------*/
   public function insert() {
      $pb = D('PromoteBlock');
      if($pb->create()){      
          $pbid = $pb->add();
          if($pbid){ 
             $this->success('推荐块新增成功!');
          } else {
             $this->error('推荐块新增失败!');
          }
      }else{
        $this->error($pb->getError());  
      }
   }
   /*----------------------------------------------------------------------*/
   public function edit(){
      $this->assign('block',D('PromoteBlock')->getBlockList());
      $this->vo = M('PromoteBlock')->find(I('id'));
      $this->display();
   }
   /*----------------------------------------------------------------------*/
   public function update() {
      $pb = D('PromoteBlock');
      if($pb->create()){      
          if($pb->save()){ 
             $this->success('推荐块编辑成功!');
          } else {
             $this->error('推荐块编辑失败!');
          }
      }else{
        $this->error($pb->getError());  
      }
   }
//----------------------------------------
}

?>