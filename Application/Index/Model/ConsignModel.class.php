<?php
namespace Index\Model;
use Think\Model\RelationModel;
class ConsignModel extends RelationModel{
   //-----------------关联
   protected $_link = array(
             'cat' => array(
                    'mapping_type'  => self::BELONGS_TO,
                    'class_name'    => 'Category',
                    'foreign_key'   => 'cat_id',
                    'mapping_name'  => 'cat',
                    'mapping_fields'=>'cat_name',
                    'as_fields'=>'cat_name',
             ),
   ); 
   //------------------自动验证
   protected $_validate = array(
     array('cat_id','require','商品分类必需!'),
     array('public_attr_ids','require','公有属性必需!'), 
     array('goods_name','require','商品名称必需!'), 
     array('goods_img','require','商品图片必需!'), 
     array('goods_param','require','商品参数必需!'),
     array('goods_detail','require','商品详细必需!'),
   );
   //------------------自动完成
   protected $_auto = array ( 
                 array('uid','current_uid',1,'function'),
                 array('add_time','time',1,'function'),
                 array('update_time','time',2,'function'),
                 array('attr_des','serialize',3,'function'),
                 array('attr_val','attr_val',3,'callback'),         
   );
   
   //序列化属性值
   public function attr_val(){
           $attr_match = I('attr_match');
           $attr = I('attr');
           $price = I('price');
           $num = I('num');
           $img = I('img');
           //-------------------------------
           $res = array();
           if(I('multi') > 0){
              foreach(I('price') as $k=>$v){
                 $res[$k]['attr'] = $attr[$k];
                 $res[$k]['attr_match'] = $attr_match[$k];
                 $res[$k]['price'] = $price[$k];
                 $res[$k]['num'] = $num[$k];
                 $res[$k]['img'] = $img[$k];
              } 
           }else{
              $res['num'] = I('num/d');
              $price = I('price/f');
              $res['price'] = (string)$price;
           }
           return serialize($res);
   }
//----------------------------------
}

?>