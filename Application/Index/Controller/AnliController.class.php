<?php
namespace Index\Controller;
use Think\Controller;
class AnliController extends CommonController{
   private $anli_cat_id = 15;    //安利区分类id
   //-----------------------------------
   public function index(){
      $this->assign('catlist',D('ArticleCategory')->subCat($this->anli_cat_id));
      $this->page_title = '安利区';
      $this->display();
   }
   //-----------------------------------
   public function show(){  
      $map['cat_id'] = I('get.cat/d');
      $map['status'] = 2;
      $this->mapSearch('Article',$map,$relation=false,$field='id,uid,title,title_img,comment_num,collect_num,like_num',$orderby='');
      $this->assign('catlist',D('ArticleCategory')->subCat($this->anli_cat_id));
      $this->assign('cat_id',I('get.cat/d'));
      $this->display();
   }
//----------------------------------------------
}

?>