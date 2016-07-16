<?php
namespace Admin\Model;
use Think\Model\RelationModel;
use Common\Util\TableTree;
class PromoteBlockModel extends RelationModel{   
    //------------------------------------
     public function getBlockList() {
          $blocklist = $this->order('id')->select();
          $tableTree = new TableTree;
          $tableTree->tree($blocklist,'id','pid','desc');
          return $tableTree->getArray();
     }
     //------------------------------------
     public function getBlockArray() {
          $block_list = $this->order('id')->select();
          foreach($cat_list as $v) {
               $block_arr[$v['id']] = $v['cat_name'];
          }
          return $block_arr;
     }
    //自动验证
    protected $_validate = array(
     array('rid','require','区域id必需!'), 
     array('sign','require','标识必需!'),
     array('desc','require','描述必需!'),  
    );
//----------------------------------
}

?>