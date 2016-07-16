<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class PublicMsgModel extends  RelationModel{    
    //-----------------------------------------
    protected $_validate = array(
     array('title','require','公告标题必需!'), 
     array('summary','require','摘要必需!'), 
    );
    //-----------------------------------------
    protected $_auto = array ( 
         array('add_time','time',1,'function'),
         array('update_time','time',2,'function'),
    );
//-------------------------    
}

?>