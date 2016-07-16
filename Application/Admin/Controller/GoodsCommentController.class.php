<?php
namespace Admin\Controller;  //命名空间
use Think\Controller;        //基类
use Think\Page;              //分页类
class GoodsCommentController extends CommonController {
  private $status     = array('1'=>'显示','0'=>'隐藏');
//--------------------------------------------------
   public function index() {  
      if(IS_POST){
             if(I('comment'))     $map['comment'] = array('like','%'.I('comment').'%');
             if(I('user'))        $map['uid'] = get_uid(I('user'));
             if(I('gid'))         $map['gid'] = I('gid');
             if(I('star'))        $map['star'] = I('star');
             if(I('show')!='')    $map['show'] = I('show');
      }
      $this->mapSearch('GoodsComment',$map);
      $this->display();
   }
   /*----------------------------------------------------------------------*/
   public function edit(){
      $this->vo =  M('GoodsComment')->find(I('get.id'));
      $this->display();
   }
   /*----------------------------------------------------------------------*/
   public function update() {
      $gc = M('GoodsComment');
      if($gc->create()){      
          if($gc->save()){ 
             $this->success('商品评价编辑成功!');
          } else {
             $this->error('商品评价编辑失败!');
          }
      }else{
        $this->error($gc->getError());  
      }
   }
//----------------------------------------
}

?>