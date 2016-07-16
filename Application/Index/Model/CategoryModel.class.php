<?php
namespace Index\Model;
use Think\Model\RelationModel;
class CategoryModel extends RelationModel{
    //------------------------------
	protected $_link = array(
		'Category' => array(
			'mapping_type'=>self::HAS_MANY,
			'mapping_name'=>'subitem', //自定义
			'mapping_order'=>'sort',
			'parent_key'=>'pid',//很关键
		),
	);
    //------------------------------
    public function catList(){ 
        if(!empty(F('catList'))){
            return F('catList');
        }else{
            $map['level'] = 3;
            $map['show'] = 1;
            $catList = $this->where($map)->field('id,cat_name')->order('sort')->select();
            F('catList',$catList);
            return $catList;
        }
    }
    //------------------------------
    public function catExist($cat_id){ 
        foreach(F('catList') as $k=>$v){
            if($v['id']==$cat_id){
                return true;
            }
        }
        return false;
    }
//-----------------------------------------
}

?>