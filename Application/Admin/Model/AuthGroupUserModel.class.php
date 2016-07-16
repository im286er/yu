<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class AuthGroupUserModel extends RelationModel{ 
        //--------------字段映射
         protected $_map = array(
          'group_groupId' =>'group_ids', // 把表单中name映射到数据表的username字段
         );
         //--------------自动验证
         protected $_validate = array(
             array('uid','unique','该用户已经在管理组',1,'unique',1),
             array('uid','checkUserId','没有该用户！',1,'function',3),
             array('group_ids','require','未选择用户组'), //默认情况下用正则进行验证
         );
         //---------------自动完成
         protected $_auto = array ( 
                 array('add_time','time',1,'function'),  
         );
//--------------------------------------------------------------------------------
}

?>