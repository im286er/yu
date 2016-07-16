<?php
namespace Index\Controller;
use Think\Controller;
class SaleController extends CommonController{
    //----------------------------------
	public function index(){
        $map['on_sale'] = 1;
        if(I('get.s/d')) $map['status'] = I('get.s/d');
        $this->assign('catlist',D('Category')->catList());
        $this->mapSearch('Goods',$map,$relation=false,$field='id,goods_name,goods_img,status,original',$orderby='',$listRows=50);
        $this->page_title = '通贩区';
        $this->display();
	}
//----------------------------------------------
}
?>