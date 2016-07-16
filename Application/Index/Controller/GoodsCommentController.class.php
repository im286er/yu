<?php
namespace Index\Controller;
use Think\Controller;
class GoodsCommentController extends CommonController {
   //---------------------------------------
   public function doComment() {
      $gc = D('GoodsComment');
      if (!$gc->create()){  
              $this->error($gc->getError());
      }else{
        $gcid=$gc->add();
        if($gcid){
            M()->startTrans(); //开始事务
            $map['gpid'] = I('gpid/d');
            $map['order_id'] = I('order_id/d');
            M('OrderGoods')->where($map)->save(array('comment'=> 1 ));
            M('Goods')->where('id='.I('gid/d'))->setInc('comment_num',1);
            M()->commit();
            $msg['code'] = 1;
            $msg['msg'] = '评价成功';
        }
        $this->ajaxReturn($msg);
      } 
   }
   //-------------------------------
   public function show(){
     $comment_num = I('comment_num/d');
     $listRows = 10;
     $totalPage = ceil($comment_num/$listRows);
     $currentPage = I('p/d')==''? 1 : I('p/d');
     $gid = I('gid/d');
     if($gid){
        $list = M('GoodsComment')->where('gid='.$gid)
                                 ->order('id DESC')
                                 ->page($currentPage.','.$listRows)->select();
     }
     $this->assign('list',$list);
     $this->assign('totalPage',$totalPage);
     $this->assign('currentPage',$currentPage);
     $this->display();
   }
//------------------------------------
}

?>