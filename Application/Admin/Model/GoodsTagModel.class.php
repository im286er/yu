<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class GoodsTagModel extends RelationModel { 
    
    //-----------------------------关联模型
     protected $_link = array(
         'gids' => array(
                'mapping_type'  => self::HAS_MANY,
                'class_name'    => 'GoodsTagMap',
                'foreign_key'   => 'tag_id',
                'mapping_name'  => 'gids',
         ),
     );
    //-----------------------------搭配数组
    public function mapArr($tag,$gid){  
        $res = array();
        foreach($tag as $k=>$v){
            $res[$k]['tag_id'] = $this->hasItem($v);
            $res[$k]['gid'] = $gid;
        }
        return $res;
    } 
    //-----------------------------确定是否存在标签,没有即刻添加
    public function hasItem($tag){
      $map['tag_name'] = $tag;
      $res = $this->where($map)->find();
      if($res['tag_id']){
        return $res['tag_id'];
      }else{
        $data['tag_name'] = $tag;
        return $this->add($data);
      }
    }
//--------------------------------------------------------------------------------
}

?>