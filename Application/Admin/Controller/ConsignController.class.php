<?php
namespace Admin\Controller;  //命名空间
use Think\Controller;        //基类
class ConsignController extends CommonController {
   //----------------------------------------------------
   public function index(){
      //商品分类信息获取
      $this->assign('catlist',D('Category')->catList());
      $map = array();
      if(IS_POST){
         if(I('user'))        $map['uid'] = get_uid(I('user'));
         if(I('goods_name'))  $map['goods_name'] = array('like','%'.I('goods_name').'%');
         if(I('status'))      $map['status'] = I('status');
         if(I('cat_id'))      $map['cat_id'] = I('cat_id');
      }
      $this->mapSearch('Consign',$map);
      $this->display();
   }
   //----------------------------------------------------
   public function edit(){
      $this->assign('vo',M('Consign')->find(I('id')));
      $this->display();
   }
   //----------------------------------------------------
   public function update(){
      $cs = D('Consign');   
      if($cs->create()){ 
        if($cs->save()){ 
             $cs->statusNotify(I('rec_uid'),I('goods_name'),I('status'),I('id'));
             $this->success('寄售状态修改成功!已发送私信!');
        } else {
             $this->error('寄售状态修改失败!');
        }
      }else{
        $this->error($cs->getError());  
      }
   }
   //----------------------------------------------------
   public function detail(){
      $this->assign('row',D('Consign')->relation('cat')->find(I('get.cid/d')));
      $this->display();
   }
//----------------------------------------
}

?>