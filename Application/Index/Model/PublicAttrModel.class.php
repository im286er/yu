<?php
namespace Index\Model;
use Think\Model\RelationModel;
class PublicAttrModel extends RelationModel{
    //----------------------
	protected $_link = array(
		'sub' => array(
			'mapping_type'=>self::HAS_MANY,
            'class_name'    => 'PublicAttr',
			'mapping_name'=>'sub', //自定义
			'mapping_order'=>'sort',
			'parent_key'=>'pid',//很关键，自关联
		),
	
	);
    //----------------------
    public function subList($cat_id){
        $map['cat_id'] = $cat_id;
        $map['pid'] = 0;
        return $this->where($map)->relation('sub')->select();
    }
//----------------------
}

?>