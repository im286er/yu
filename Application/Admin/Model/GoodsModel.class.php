<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class GoodsModel extends RelationModel{ 
     //-----------------------------自动验证
     protected $_validate = array(
         array('c_user','checkUser','没有该委托人！',2,'function',3), //2代表值不为空时候验证  3代表新增修改都验证
     );
     //-----------------------------关联模型
     protected $_link = array(
         'private' => array(
                'mapping_type'  => self::HAS_MANY,
                'class_name'    => 'GoodsPrivate',
                'foreign_key'   => 'gid',
                'mapping_name'  => 'private',
         ),
         'cat' => array(
                'mapping_type'  => self::BELONGS_TO,
                'class_name'    => 'Category',
                'foreign_key'   => 'cat_id',
                'mapping_name'  => 'cat',
                //'mapping_order' => 'id asc',
                'mapping_fields'=>'cat_name',
                'as_fields'=>'cat_name',
         ),
         'tag' => array(
                'mapping_type'  => self::HAS_MANY,
                'class_name'    => 'GoodsTagMap',
                'foreign_key'   => 'gid',
                'mapping_name'  => 'tag',
         ),
     );
     //-----------------------------商品添加修改自动完成
     protected $_auto = array ( 
             array('add_time','time',1,'function'),  
             array('update_time','time',2,'function'), 
             array('onsale_time','strtotime',3,'function'),
             array('c_uid','getCuid',3,'callback'), 
             array('attr_des','attrDes',1,'callback'),  
     );
     //-----------------------------自动添加委托人uid
     public function getCuid(){
         return get_uid(I('c_user'));
     }
     //-----------------------------添加私有属性描述
     public function attrDes(){
         return I('multi') > 0 ? serialize(I('attr_des')):"";
     }
//--------------------------------------------------------------------------------
}

?>