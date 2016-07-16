<?php
namespace Admin\Controller;  //命名空间
use Think\Controller;        //基类
use Think\Page;              //分页类
class PageController extends CommonController {

//--------------------------------------------------
   public function index() {   
      $this->mapSearch('Page',$map);
      $this->display();
   }
//--------------------------------------------------  
   public function add() {
      $this->display();
   }
//-------------------------------------------------
   public function insert() {
      $model = M('Page');
      $model->create();
      $model->content = word_filter($model->content);
      $model->add_time = time();
      if($model->add()) {
         $this->success('页面添加成功!');
      } else {
         $this->error('页面添加失败!');
      }
   }
//-------------------------------------------------  
   public function edit() {
      $model = M('Page');
      $pageinf = $model->find(I('id'));
      $this->vo = $pageinf;
      $this->display();
   }
//-------------------------------------------------
   public function update() {
      $model = M('Page');
      $model->create();
      $model->content = word_filter($model->content);
      $model->update_time = time();
      if($model->save()) {
         $this->success('页面编辑成功!');
      } else {
         $this->error('页面编辑失败!');
      }
   }
//----------------------------------------
}

?>