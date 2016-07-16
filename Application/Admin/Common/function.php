<?php
//----------------------------------------------------------------------权限管理页
//管理组ids变str
function groupName($group_ids) {
  $map['id'] = array('IN',$group_ids);
  $res =  M('AuthGroup')->where($map)->field('title')->select();
  foreach($res as $v){
        $str.=$v['title'].',';
    }
  return rtrim($str,',');
}
//----------------------------------------------------------------------寄售页
//公有属性ids变str ，寄售显示用
function publicAttrStr($public_attr_ids) {
    $map['id']  = array('IN',$public_attr_ids); 
    $res = M('PublicAttr')->where($map)->field('title')->select();
    foreach($res as $v){
        $str.=$v['title'].',';
    }
    return rtrim($str,','); 
}
//----------------------------------------------------------------------------------------------------------------
?>