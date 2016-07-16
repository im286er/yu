<?php
namespace Index\Controller;
use Think\Controller;
class CreateController extends CommonController{
   private $create_cat_id = 3;   //创作区分类id
   //-----------------------------------
   public function index(){
      $this->assign('catlist',D('ArticleCategory')->subCat($this->create_cat_id));
      $this->page_title = '创作区';
      $this->display();
   }
   //-----------------------------------
   public function show(){  
      $map['cat_id'] = I('get.cat/d');
      $map['status'] = 2;
      $this->mapSearch('Article',$map,$relation=false,$field='id,title,title_img,summary,uid,cat_id,add_time',$orderby='',$listRows='20');
      $this->assign('catlist',D('ArticleCategory')->subCat($this->create_cat_id));
      $this->assign('cat_id',I('get.cat/d'));
      $this->display();
   }
//----------------------------------------------
}

?>