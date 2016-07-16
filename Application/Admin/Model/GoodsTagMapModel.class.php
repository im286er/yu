<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class GoodsTagMapModel extends RelationModel { 
    //-------------------------------------------   
    public function addMap($map){  
       foreach($map as $k=>$v){
          $dataArr[] = array('gid'=>$v['gid'],'tag_id'=>$v['tag_id']);
       }
       return $this->addAll($dataArr); 
    } 
    //------------------------------------------
    public function delMap($gid){
      $map['gid'] = $gid;
      $this->where($map)->delete();
      return $this;
    }
//--------------------------------------------------------------------------------
}

?>