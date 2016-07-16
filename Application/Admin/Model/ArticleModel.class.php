<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class ArticleModel extends RelationModel{   
    protected $_validate = array(
     array('cat_id','require','文章必需!'), 
     array('title','require','文章标题必需!'), 
     array('title_img','require','文章图片必需!'), 
    );
    
    protected $_auto = array ( 
         array('uid','current_uid',1,'function'),
         array('add_time','time',1,'function'),
         array('update_time','time',2,'function'),
    );
    
	protected $_link = array(
             'article_cat' => array(
                    'mapping_type'  => self::BELONGS_TO,
                    'class_name'    => 'ArticleCategory',
                    'foreign_key'   => 'cat_id',
                    'mapping_name'  => 'cat',
                    //'mapping_order' => 'id asc',
                    'mapping_fields'=>'cat_name',
                    'as_fields'=>'cat_name',
             ),
    );
//----------------------------------
}

?>