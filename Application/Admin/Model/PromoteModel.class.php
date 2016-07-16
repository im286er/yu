<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class PromoteModel extends RelationModel{  
    //自动验证
    protected $_validate = array(
     array('title','require','标题必需!'), 
     array('img','require','图片必需!'), 
     array('link','require','链接必需!'), 
    );
    //自动完成
    protected $_auto = array (
         array('sorttime','time',1,'function'), // 对update_time字段在更新的时候写入当前时间戳
    );
    //关联模型
    protected $_link = array(
         'block' => array(
                'mapping_type'  => self::BELONGS_TO,
                'class_name'    => 'PromoteBlock',
                'foreign_key'   => 'bid',
                'mapping_name'  => 'block',
                'mapping_fields'=>'desc,sign',
                'as_fields'=>'desc,sign',
         ),
    );
//----------------------------------
}

?>