<?php
namespace Admin\Controller;
use Think\Controller;
class ArticleCommentController extends CommonController{
	//----------------------------
	public function index(){
	    if(IS_POST){
             if(I('user'))      $map['uid'] = get_uid(I('user'));
             if(I('aid'))       $map['aid'] = I('aid');
        }
        $this->mapSearch('ArticleComment',$map);
	    $this->display();
	}
//----------------------------------------
}

?>