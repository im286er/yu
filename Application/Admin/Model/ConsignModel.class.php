<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class ConsignModel extends RelationModel{ 
     //---------------------------------
     protected $_link = array(
         'cat' => array(
                'mapping_type'  => self::BELONGS_TO,
                'class_name'    => 'Category',
                'foreign_key'   => 'cat_id',
                'mapping_name'  => 'cat',
                //'mapping_order' => 'id asc',
                'mapping_fields'=>'cat_name',
                'as_fields'=>'cat_name',
         ),
     );
     //-----------------------------商品添加修改自动完成
     protected $_auto = array (
             array('update_time','time',2,'function'), 
     );
     //-----------------------------用户私信发送,状态改变通知
     public function statusNotify($c_uid,$goods_name,$status,$cid,$summary=''){
        $consign_status = C('CONSIGN_STATUS');
        $send_uid = session('uid');
        $rec_uid = $c_uid;
        $title = "寄售商品【".$goods_name."】".$consign_status[$status];
        $link = U('Index/Consign/detail',array('cid'=>$cid));
        priMsgSend($send_uid,$rec_uid,$title,$summary,$link,'Consign',$cid);
        return $this;
     }
//--------------------------------------------------------------------------------
}

?>