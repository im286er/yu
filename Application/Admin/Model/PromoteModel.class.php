<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class PromoteModel extends RelationModel{  
    //�Զ���֤
    protected $_validate = array(
     array('title','require','�������!'), 
     array('img','require','ͼƬ����!'), 
     array('link','require','���ӱ���!'), 
    );
    //�Զ����
    protected $_auto = array (
         array('sorttime','time',1,'function'), // ��update_time�ֶ��ڸ��µ�ʱ��д�뵱ǰʱ���
    );
    //����ģ��
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