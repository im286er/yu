<?php
namespace Index\Model;
use Think\Model;
class ArticleCategoryModel extends Model{
   private $cat_controller = array('3'=>'Create','15'=>'Anli','22'=>'Service');
   private $cat_area = array('3'=>'创作区','15'=>'安利区','22'=>'服务区');
   //-------------------------------
   public function subCat($pid){  
      $map['level'] = 3;
      $map['show'] = 1;
      $map['pid'] = $pid;
      return $this->where($map)->field('id,cat_name')->order('sort')->select();
   }
   //--------------------------------------------
   public function catLink($cat_id){
      $cat_pid = $this->where('id='.$cat_id)->getField('pid');
      $cat_controller = $this->cat_controller; 
      $cat_area = $this->cat_area;  
      $res['link'] = '/Index/'.$cat_controller[$cat_pid].'/show/id/'.$cat_id.'.html'; 
      $res['cat_area'] = $cat_area[$cat_pid];
      return $res;
   }
//-----------------------------------
}

?>