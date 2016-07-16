<?php
namespace Admin\Controller;  //命名空间
use Think\Controller;        //基类
class PromoteController extends CommonController {
   //-----------------------------------------
   public function index(){
      $map = array();
      $map['bid'] = 2;
      if(IS_POST){
         if(I('title'))       $map['title'] = array('like','%'.I('title').'%');
         if(I('bid'))         $map['bid'] = I('bid');
      }
      $this->assign('block',D('PromoteBlock')->getBlockList());
      $this->mapSearch('Promote',$map);
      $this->display();
   }
   /*----------------------------------------------------------------------*/
   public function add(){
      $this->assign('block',D('PromoteBlock')->getBlockList());
      $this->display();
   }
   /*----------------------------------------------------------------------*/
   public function insert() {
      $p = D('Promote');
      if($p->create()){      
          $pid = $p->add();
          if($pid){ 
             $this->success('推荐项新增成功!');
          } else {
             $this->error('推荐项新增失败!');
          }
      }else{
        $this->error($p->getError());  
      }
   }
   /*----------------------------------------------------------------------*/
   public function edit(){
      $this->assign('block',D('PromoteBlock')->getBlockList());
      $this->assign('vo',M('Promote')->find(I('id')));
      $this->display();
   }
   /*----------------------------------------------------------------------*/
   public function update() {
      $p = D('Promote');
      if($p->create()){      
          if($p->save()){ 
             $this->success('推荐项编辑成功!');
          } else {
             $this->error('推荐项编辑失败!');
          }
      }else{
        $this->error($p->getError());  
      }
   }
   /*----------------------------------------------------------------------*/
   public function sortItem(){
       $data['sort'] = I('sort',0,int);
       $data['sorttime'] = time();
       M('Promote')->where('id='.I('id'))->save($data);
   }
   /*----------------------------------------------------------------------*/
   public function reSortItem(){
       $data['sort'] = 10;
       M('Promote')->where('bid='.I('bid'))->save($data);
   }
   /**
     * 推荐商品 
     * -------------------------------------
     * 
     */
   public function goods(){
     $this->assign('catlist',D('Category')->catList());
     if(I('cat_id')){
        $this->assign('cat_id',I('cat_id'));
        $this->assign('list',F('goods_promote_cat_'.I('cat_id')));
     }else{
        $this->assign('list',F('goods_promote_main'));
     }
     $this->display();
   }
   //-----------------------------------
   public function doSort(){
    $sort = I('sort');
    if(I('cat_id')){
       $cat_id = I('cat_id');
       $temp = array();
       $promote_arr =  F('goods_promote_cat_'.$cat_id);
       foreach($sort as $k=>$v){
          $temp[$k] = $promote_arr[$v];
       }
       F('goods_promote_cat_'.$cat_id,$temp);
    }else{
       $temp = array();
       $promote_arr =  F('goods_promote_main');
       foreach($sort as $k=>$v){
          $temp[$k] = $promote_arr[$v];
       }
       F('goods_promote_main',$temp);
    }
    $this->success('排序成功!');
   }
//----------------------------------------
}

?>