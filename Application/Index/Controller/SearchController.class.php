<?php
namespace Index\Controller;
use Think\Controller;
class SearchController extends CommonController{
    //----------------------------------
	public function goods(){
        //C('TOKEN_ON',false);
        $this->assign('catlist',D('Category')->catList());
        $orderby = 'id DESC ';
        if(IS_GET){
           //条件搜索
           if(I('get.s'))          $map['status'] = I('get.s/d');
           if(I('get.cat'))        $map['cat_id'] = I('get.cat/d'); 
           if(I('get.keyword'))    $map['goods_name'] = array('like','%'.I('keyword').'%'); 
        }
        $this->mapSearch('Goods',$map);
        $this->display();
	}
    //----------------------------------
    public function article(){
        //C('TOKEN_ON',false);
        $orderby = 'id DESC ';
        if(IS_GET){
           //条件搜索
           if(I('get.keyword'))    $map['title'] = array('like','%'.I('keyword').'%'); 
        }
        if(I('get.o/s')){
                 $order = I('get.o/s')==''?' id ':I('get.o/s');
                 $orderby = $order.' DESC';
        }
        $this->mapSearch('Article',$map);
        $this->display();
	}
//----------------------------------------------
}
?>