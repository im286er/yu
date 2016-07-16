<?php
namespace Admin\Controller;  //命名空间
use Think\Controller;        //基类
class OrderRefundController extends CommonController {
//--------------------------------------------------
   public function index() {
      $map = array();
      if(IS_POST){
         if(I('user'))         $map['uid'] = get_uid(I('user'));
         if(I('order_sn'))    $map['order_sn'] = I('order_sn');
      }
      $this->mapSearch('OrderGoodsRefund',$map);
      $this->display();
   }
   /*----------------------------------------------------------------------*/
   public function edit() {
      $vo =  M('OrderGoodsRefund')->find(I('id'));
      $this->assign('vo',$vo);
      $this->display();
   }
   /*----------------------------------------------------------------------*/
   public function update() {
      $model = D('OrderGoodsRefund');
      if($model->create()){      
          if($model->save()){ 
             $this->success('退换货状态编辑成功!');
          } else {
             $this->error('退换货状态编辑失败!');
          }
      }else{
        $this->error($model->getError());  
      }
   }
//----------------------------------------
}

?>