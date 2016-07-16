<?php
namespace Admin\Controller;
use Think\Controller;
class CategoryController extends CommonController {
   //-------------------
   public function index() {
      $this->assign('list',D('Category')->catList());
      $this->display();
   }
   //----------------------------
   public function add() {
      $this->assign('catlist',D('Category')->catList());
      $this->display();
   }
   //----------------------------
   public function insert() {
      $category = M('Category');
      $category->create();
      if($category->add()) {
         $this->success('商品分类添加成功！');
      } else {
         $this->error('商品分类添加失败！');
      }
   }
   //----------------------------
   public function edit() {
      $this->assign('catlist',D('Category')->catList());
      $this->vo = M('Category')->find(I('id'));
      $this->display();
   }
   //----------------------------  
   public function update() {
      $model = M('Category');
      $model->create();
      if($model->save()) {
         $this->success('商品分类编辑成功！');
      } else {
         $this->error('商品分类编辑失败！');
      }
   }
//----------------------------
}
?>