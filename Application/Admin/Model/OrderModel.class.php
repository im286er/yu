<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class OrderModel extends RelationModel{ 
        //-------------------------
         protected $_link = array(
              'order_goods' => array(
                    'mapping_type'  => self::HAS_MANY,
                    'class_name'    => 'OrderGoods',
                    'foreign_key'   => 'order_id',
                    'mapping_name'  => 'order_goods',
              ),
         );
//--------------------------------------------------------------------------------
}

?>