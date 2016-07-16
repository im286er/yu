<?php
namespace Admin\Controller;
use Think\Controller;
class GoodsTagController extends CommonController {
    //--------------------
    public function index(){
      $map = array();
      if(IS_POST){
         if(I('status'))      $map['status'] = I('status');
      }
      $this->mapSearch('GoodsTag',$map,'tag_id DESC');
      $this->display();
    }
    //--------------------修改状态
    public function changeStatus(){
       $gt = D('GoodsTag');
       $pk = $gt->getPk();
       $map[$pk] = array('IN',I($pk));
       $gt->where($map)->save(array('status'=>I('get.s/d')));
       $this->success('修改成功!');
    }
    //--------------------关联删除
    public function reldelete(){
       $gt = D('GoodsTag');
       $pk = $gt->getPk();
       $map[$pk] = array('IN',I($pk));
       $gt->relation(true)->where($map)->delete();
       $this->success('修改成功!');
    }
//--------------------------------------------------
}
?>