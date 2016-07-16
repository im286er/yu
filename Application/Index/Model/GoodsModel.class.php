<?php
namespace Index\Model;
use Think\Model\RelationModel;
class GoodsModel extends RelationModel{
	//---------------------------------------------------
    protected $_link = array(
             'cat' => array(
                    'mapping_type'  => self::BELONGS_TO,
                    'class_name'    => 'Category',
                    'foreign_key'   => 'cat_id',
                    'mapping_name'  => 'cat',
                    'mapping_fields'=>'cat_name',
                    'as_fields'=>'cat_name',
             ),
             'tag' => array(
                'mapping_type'  => self::HAS_MANY,
                'class_name'    => 'GoodsTagMap',
                'mapping_fields'=>'tag_id',
                'foreign_key'   => 'gid',
                'mapping_name'  => 'tag',
             ),
   );
   //-------------------------------商品详细信息
    public function baseInfo($gid){
        $res = $this->relation('cat')->cache('goods_detail_'.$gid)->find($gid);
        if($res['id']){
           if($res['multi'] > 0){
            $res['attr_des'] = unserialize($res['attr_des']);
           } 
           return $res; 
        }else{
           return false;  
        }
    }
//------------------------------------------------------
}

?>