<?php
namespace Index\Controller;
use Think\Controller;
class ServiceController extends CommonController{
    private $service_cat_id = 22;   //创作区分类id
	public function index(){
        $this->assign('catlist',D('ArticleCategory')->subCat($this->service_cat_id));
        $this->page_title = '服务区';
        $this->display();
	}
    //-----------------------------------
    public function show(){  
      $map['cat_id'] = I('get.cat/d');
      $map['status'] = 2;
      $this->mapSearch('Article',$map);
      $this->assign('catlist',D('ArticleCategory')->subCat($this->service_cat_id));
      $this->assign('cat_id',I('get.cat/d'));
      $this->display();
    }
    //-----------------------------------
    public function printing(){  
      $this->display();
    }
//----------------------------------------------
}
?>