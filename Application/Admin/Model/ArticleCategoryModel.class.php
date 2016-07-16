<?php
namespace Admin\Model;
use Think\Model;
use Common\Util\TableTree;
class ArticleCategoryModel extends Model{
     //------------------------------------
     public function catList() {
          $categorylist = $this->order('sort')->select();
          $tableTree = new TableTree;
          $tableTree->tree($categorylist,'id','pid','cat_name');
          return $tableTree->getArray();
     }
     //------------------------------------
}

?>