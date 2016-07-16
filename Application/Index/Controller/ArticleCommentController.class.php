<?php
namespace Index\Controller;
use Think\Controller;
class ArticleCommentController extends CommonController{
   //-----------------------------------
   public function add(){
    if(IS_AJAX && session('uid') && I('aid/d')){
        $data = array(
                  'aid'=>I('aid/d'),'uid'=>session('uid'),
                  'content'=>I('content'),'top_id'=>I('top_id/d'),
                  'to_uid'=>I('to_uid/d'),'add_time'=>time()
                );
        $aid = M('ArticleComment')->add($data);
        if($aid){ 
             $msg['statusCode'] = true;
             $msg['add_time'] = date('Y-m-d H:i:s',$data['add_time']);
             $msg['id'] = $aid;
             if(I('top_id/d')>0){
                M()->startTrans(); //开始事务
                $map['id'] = $data['top_id'];
                M('ArticleComment')->where('id='.I('top_id/d'))->setInc('reply_num',1); 
                M('Article')->where('id='.I('aid/d'))->setInc('reply_num',1);
                M()->commit();
             }
             $this->ajaxReturn($msg);
        }
     }
   } 
   //-----------------------------------
   public function getComment(){ 
     if(IS_AJAX){
          $map['aid'] = I('aid');
          $map['top_id'] = 0;
          $this->mapSearch('ArticleComment',$map,$relation='relpy',$field='',$orderby='',$listRows=10);
          echo $this->fetch('ArticleComment:outside');
     }
   } 
   //-----------------------------------
   public function insideComment(){ 
      $map['top_id'] = I('top_id');
      $order = I('order')==''?'add_time':I('order');
      $currentPage = I('p')==''?1:I('p');
      $prePage = $currentPage==1?1:$currentPage-1;
      $nextPage = $currentPage==$totalPage?$totalPage:$currentPage+1;
      $sort = 'ASC';
      $model = M('ArticleComment');
      $list = $model->where($map)->order($order.' '.$sort)->page($currentPage.',4')->select();
      $this->insidecomment = $list;
      $this->assign('prePage',$prePage);
      $this->assign('nextPage',$nextPage);
      $this->assign('reply_num',I('reply_num'));
      $this->assign('currentPage',$currentPage);
      $this->assign('order',$order);
      echo $this->fetch('ArticleComment:inside');
   } 
   //-----------------------------------
   public function likes(){ 
      M('ArticleComment')->where('id='.I('id'))->setInc('likes',1);
   }
//----------------------------------------------
}

?>