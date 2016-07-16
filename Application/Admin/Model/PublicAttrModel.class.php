<?php
namespace Admin\Model;
use Think\Model\RelationModel;
use Common\Util\TableTree;
class PublicAttrModel extends  RelationModel{    
    protected $_link = array(
             'cat' => array(
                    'mapping_type'  => self::BELONGS_TO,
                    'class_name'    => 'Category',
                    'foreign_key'   => 'cat_id',
                    'mapping_name'  => 'cat',
                    //'mapping_order' => 'id asc',
                    'mapping_fields'=>'cat_name',
                    'as_fields'=>'cat_name',
             ),
             'sub' => array(
                    'mapping_type'  => self::HAS_MANY,
                    'class_name'    => 'PublicAttr',
                    'foreign_key'   => 'pid',
                    'parent_key'   => 'pid',
                    'mapping_name'  => 'sub',
                    //'mapping_order' => 'add_time asc',
                    //'mapping_limit' =>'4',
                    'mapping_fields' =>'id,title', 
             ),
    );
    //-------------------------------------
    public function getAttrList($cat_id) {
          $list = $this->where('cat_id='.$cat_id)->order('sort asc')->select();
          $tableTree = new TableTree;
          $tableTree->tree($list);
          return $tableTree->getArray();
    }
    //-------------------------------------
    public function getParentAttrList($cat_id) {
        $where['cat_id'] = $cat_id;
		$where['pid'] = 0;
		$attrlist = $this->where($where)->field('id,title')->select();
		$result = Array();
		$result[] = array('0','顶层');
		foreach($attrlist as $v){
			$result[] = array($v['id'],$v['title']);
		}
        return $result;
    }   
    //-------------------------------------
    public function chooseItem($cat_id) {
        $map['cat_id'] = $cat_id;
        $map['pid'] = 0;
        return $this->relation(true)->field('id,title')->where($map)->select();
    } 
//-------------------------    
}

?>