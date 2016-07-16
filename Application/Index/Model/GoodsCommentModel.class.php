<?php
namespace Index\Model;
use Think\Model\RelationModel;
class GoodsCommentModel extends RelationModel{ 
         //------------------自动验证
         protected $_validate = array(
             array('gid','require','商品id必需!'),
             array('comment','require','评论必需!'), 
             array('star','require','星级必需!'), 
         );
         //------------------自动完成
         protected $_auto = array ( 
                     array('uid','current_uid',1,'function'),
                     array('add_time','time',1,'function'),
         );
//--------------------------------------------------------------------------------
}

?>