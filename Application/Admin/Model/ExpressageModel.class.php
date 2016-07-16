<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class ExpressageModel extends RelationModel{   

	protected $_link = array(
             'area' => array(
                    'mapping_type'  => self::BELONGS_TO,
                    'class_name'    => 'Area',
                    'foreign_key'   => 'area_id',
                    'mapping_name'  => 'area',
                    //'mapping_order' => 'id asc',
                    'mapping_fields'=>'areaname',
                    'as_fields'=>'areaname',
             ),
    );
//----------------------------------
}

?>