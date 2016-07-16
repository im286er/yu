<?php
namespace Admin\Model;
use Think\Model;
use Common\Util\TableTree;
class AuthRuleModel extends  Model //RelationModel
{
    public function getRuleList() {
          $rulelist = $this->order('sort')->select();
          $tableTree = new TableTree;
          $tableTree->tree($rulelist,'id','pid','name');
          return $tableTree->getArray();
     }
}

?>