<?php
namespace Admin\Controller;
use Think\Controller;
class ArticleCategoryController extends CommonController {
   //----------------------------
   public function index() {
      $this->assign('list',D('ArticleCategory')->catList());
      $this->display();
   }
   //----------------------------
   public function add() {
      $this->assign('catlist',D('ArticleCategory')->catList());
      $this->display();
   }
   //----------------------------
   public function insert() {
      $model = M('ArticleCategory');
      $model->create();
      if($model->add()) {
         $this->success('文章分类添加成功！');
      } else {
         $this->error('文章分类添加失败！');
      }
   }
   //----------------------------
   public function edit() {
      $this->assign('catlist',D('ArticleCategory')->catList());
      $this->vo = M('ArticleCategory')->find(I('id'));
      $this->display();
   }
   //----------------------------  
   public function update() {
      $model = M('ArticleCategory');
      $model->create();
      if($model->save()) {
         $this->success('文章分类编辑成功！');
      } else {
         $this->error('文章分类编辑失败！');
      }
   }
//----------------------------------------
}

?>