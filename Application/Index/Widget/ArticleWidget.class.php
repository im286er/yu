<?php
namespace Index\Widget;
use Think\Controller;
class ArticleWidget extends Controller{   
    //---------------------------最新文章
	public function newest($catlist,$service=0){   
          foreach($catlist as $v){
             $cat_ids.=$v['id'].','; 
          }
          $cat_str = rtrim($cat_ids,',');
          $map['cat_id'] = array('IN',$cat_str);
          $map['status'] = 2;
          $list = D('Article')->relation('cat')->where($map)
                              ->field('id,title,title_img,summary,uid,cat_id,add_time')
                              ->order('id DESC')->limit(12)->select();
         
          $this->assign('list',$list);
          if($service){
          	$this->display('Widget:Article:newest_service');
          }else{
          	$this->display('Widget:Article:newest');
          }
	}
    //---------------------------热门
	public function heat($cat_id){  
	      $map['cat_id'] = $cat_id;
          $map['status'] = 2;
          $list = D('Article')->cache('Article_heat_'.$cat_id,60*60)
                              ->relation('cat')->where($map)
                              ->field('id,title,title_img,cat_id')
                              ->order('views DESC')->limit(10)->select();   
          $this->assign('list',$list);
          $this->display('Widget:Article:heat');
	}
    //---------------------------用户相关投稿
	public function contribute($uid,$cat_id){  
	      $map['uid'] = $uid;
	      $map['cat_id'] = $cat_id;
          $list = D('Article')->cache('Article_user_contribute_'.$uid.'_cat_'.$cat_id,60*60)
                              ->relation('cat')->where($map)
                              ->field('id,title,title_img,cat_id')
                              ->order('views DESC')->limit(10)->select();                                      
          $this->assign('list',$list);
          $this->display('Widget:Article:contribute');
	}
//------------------------------------------
}
?>