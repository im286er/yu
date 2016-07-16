<?php
namespace Index\Controller;
use Think\Controller;
class IndexController extends CommonController{
    //-----------------------------------------
	public function index(){
	    //网站公告
        $pub_msg = M('PublicMsg')->cache('Pub_Msg',60*60)->field('id,title')->order('id DESC')->limit(5)->select();
        //-----------------------------------
        $this->assign('site_info',$site_info);                         //全站信息
        $this->assign('pub_msg',$pub_msg);                             //公告
        $this->assign('catlist',D('Category')->catList());             //商品分类
        $this->assign('goods_promote_main',F('goods_promote_main'));   //首页商品推荐 
		$this->display();
	}
    //-----------------------------------------
	public function recommend(){
        if(I('cat_id')){
            $this->assign('goods_promote',F('goods_promote_cat_'.I('cat_id')));
        }else{
            $this->assign('goods_promote',F('goods_promote_main'));
        }
		$this->display();
	}
	/*-------------------------------------------*/
    public function logout() {
      session('[destroy]');
	  redirect(U('Index/Login/index'));
      return false;
    }
//----------------------------------------------
}
?>