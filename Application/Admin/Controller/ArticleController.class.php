<?php
namespace Admin\Controller;  //命名空间
use Think\Controller;        //基类
use Think\Page;              //分页类
class ArticleController extends CommonController {
//--------------------------------------------------
   public function index() {
      $this->assign('catlist',D('ArticleCategory')->catList());
      //--------------------------------
      $map = array();
      if(IS_POST){
         if(I('user'))        $map['uid'] = get_uid(I('user'));
         if(I('title'))       $map['title'] = array('like','%'.I('title').'%');
         if(I('status'))      $map['status'] = I('status');
         if(I('cat_id'))      $map['cat_id'] = I('cat_id');
      }
      $this->mapSearch('Article',$map);
      $this->display();
   }
   /*----------------------------------------------------------------------*/
   public function add() {
      $this->assign('catlist',D('ArticleCategory')->catList());
      $this->display();
   }
   /*----------------------------------------------------------------------*/
   public function insert() {
      $article = D('Article');
      if($article->create()){      
          $aid = $article->add();
          if($aid){ 
             $this->success('文章新增成功!');
          } else {
             $this->error('文章提交失败!');
          }
      }else{
        $this->error($article->getError());  
      }
   }
   /*----------------------------------------------------------------------*/
   public function edit() {
      $this->assign('catlist',D('ArticleCategory')->catList());
      $this->vo =  D('Article')->relation(true)->find(I('id'));
      $this->display();
   }
   /*----------------------------------------------------------------------*/
   public function update() {
      $article = D('Article');
      if($article->create()){      
          if($article->save()){ 
             if(I('msg')==1){
                 priMsgSend(session('uid'),I('rec_uid'),'投稿上线通知','投稿" '.I('title').' " 已通过审核上线','/Index/Article/detail/id/'.I('id').'.html','Article',I('id'));
                 $msg = '已发送用户信息';
             } 
             $this->success('文章编辑成功!'.$msg);
          } else {
             $this->error('文章编辑失败!');
          }
      }else{
        $this->error($article->getError());  
      }
   }
//----------------------------------------
}

?>