<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class AuthGroupModel extends RelationModel{ 
         //--------------自动验证
         protected $_validate = array(
             array('title','unique','该管理组已存在',1,'unique',3),
         );
//--------------------------------------------------------------------------------
}

?>