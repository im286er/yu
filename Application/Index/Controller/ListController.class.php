<?php
namespace Index\Controller;
use Think;
use Think\Controller;
use Think\Page;
class ListController extends CommonController{
    //-----------------------------------
	public function category(){
	    //C('SHOW_PAGE_TRACE',1);
	    $map = array();
        $map['cat_id'] = $cat_id = I('get.cat/d');
        if(!D('Category')->catExist($cat_id)) return false;  //没有该分类
        $this->assign('catlist',D('Category')->catList());
        if(I('get.attr/s')){
            $attr = I('get.attr/s');
            $attr_arr = explode(',',I('get.attr/s'));
            $attr_count = count($attr_arr);
            foreach($attr_arr as $k=>$v){ 
               if($v==0) $attr_count--;
               $like_str.=($v==0?'%':$v).',';
            }
            if($attr_count > 0){
              $map['public_attr_ids'] = array('LIKE',rtrim($like_str,',')); 
            }        
		}
        $this->mapSearch('Goods',$map,$relation=false,$field='id,goods_name,goods_img,status,original',$orderby='',$listRows='50');
        
        $this->assign('public_attr',D("PublicAttr")->subList($cat_id));
        $this->page_title = '通贩区';
		$this->display();
         
        
	}
//-----------------------------------------------------
}

?>