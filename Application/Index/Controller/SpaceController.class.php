<?php
namespace Index\Controller;
use Think\Controller;
class SpaceController extends CommonController {
   //----------------------------
   private function _userInfo(){
   	   $uid = I('get.uid/d');
   	   if($uid){
	   	return M('User')->cache('space_uid_'.$uid.'_info',60*60)->find($uid);
	   }else{
	   	redirect(U('/'));
	  	return false;
	   }
   }
   //-----------------------------------------个人空间-------------------------
   public function index(){
        $userInfo = $this->_userInfo();
      	if($userInfo['id']){
			//----------用户寄售
			$uid = $userInfo['id'];
		  	$map = array();
		  	$map['c_uid'] = $uid;$map['on_sale'] = 1;
		  	$goods = M('Goods')->where($map)->cache('space_uid_'.$uid.'_goods',60*60)
		  	                   ->field('id,on_sale,pre_sale,goods_name,goods_img,status')->order('id DESC')->limit(20)->select();        
		  	//----------用户投稿
		  	$map = array();
		  	$map['uid'] = $uid;$map['status'] = 2;
		  	$article = D('Article')->relation('cat')->where($map)->cache('space_uid_'.$uid.'_article',60*60)
		  	                       ->field('id,title,title_img,add_time,summary,cat_id')->order('id DESC')->limit(20)->select();
		  	
		  	$this->assign('userInfo',$userInfo);                       
		  	$this->assign('goods',$goods);
		  	$this->assign('article',$article);
		  	$this->page_title = '个人空间';
		  	$this->display();                                              
		}else{
			redirect(U('/'));
	  	    return false;
		}
   } 
   //-----------------------------------------个人空间 寄售商品-------------------------
   public function consign(){
      $userInfo = $this->_userInfo();
      $uid = $userInfo['id'];
      $map = array();$map['c_uid'] = $uid;$map['on_sale'] = 1;
      $this->mapSearch('Goods',$map,$relation=false,$field='id,goods_name,goods_img,status',$orderby='',$listRows=20);
      $this->assign('userInfo',$userInfo);
      $this->page_title = '个人空间-寄售';
      $this->display();
   } 
   
   //-----------------------------------------个人空间 投稿文章-------------------------
   public function contribute(){
      $userInfo = $this->_userInfo();
      $uid = $userInfo['id'];
      $map = array();$map['uid'] = $uid;$map['status'] = 2;
      $this->mapSearch('Article',$map,$relation='cat',$field='id,title,title_img,add_time,summary,cat_id',$orderby='',$listRows=20);
      $this->assign('userInfo',$userInfo);
      $this->page_title = '个人空间-投稿';
      $this->display();
   } 
  
//-------------------------------------------------------------------------
}

?>