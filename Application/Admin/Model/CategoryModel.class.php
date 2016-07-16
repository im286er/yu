<?php
namespace Admin\Model;
use Think\Model\RelationModel;
use Common\Util\TableTree;
class CategoryModel extends RelationModel{
     //-------------------------------
     public function catList() {
          $categorylist = $this->order('sort')->select();
          $tableTree = new TableTree;
          $tableTree->tree($categorylist,'id','pid','cat_name');
          return $tableTree->getArray();
     }
//-------------------------------
}

?>