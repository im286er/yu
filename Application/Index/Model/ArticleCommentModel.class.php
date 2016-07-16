<?php
namespace Index\Model;
use Think\Model\RelationModel;
class ArticleCommentModel extends RelationModel{
    //---------------------------------------
    protected $_link = array(
             'relpy' => array(
                    'mapping_type'  => self::HAS_MANY,
                    'class_name'    => 'ArticleComment',
                    'foreign_key'   => 'top_id',
                    'parent_key'   => 'top_id',
                    'mapping_name'  => 'relpy',
                    'mapping_order' => 'add_time asc',
                    'mapping_limit' =>'4',
                    //'mapping_fields' =>'content', 
             ),
    );
//-----------------------------------------------
}

?>