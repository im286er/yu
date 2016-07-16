<?php
namespace Index\Controller;
use Think\Controller;
class IndexController extends CommonController{
    //-----------------------------------------
	public function index(){
	    //��վ����
        $pub_msg = M('PublicMsg')->cache('Pub_Msg',60*60)->field('id,title')->order('id DESC')->limit(5)->select();
        //-----------------------------------
        $this->assign('site_info',$site_info);                         //ȫվ��Ϣ
        $this->assign('pub_msg',$pub_msg);                             //����
        $this->assign('catlist',D('Category')->catList());             //��Ʒ����
        $this->assign('goods_promote_main',F('goods_promote_main'));   //��ҳ��Ʒ�Ƽ� 
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