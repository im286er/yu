<?php
namespace Admin\Util;
class Auth{
      public function check($act,$uid){
        $group_ids = $this->getGroups($uid);   //获取用户所有组
        if(in_array(C('AUTH_SUPERADMIN_GROUP'),explode(',',$group_ids))){ 
            return 1;    //超级管理组直接返回
        }else{
          $rules_arr =  $this->getGroupsRule($group_ids);  //获取所有组权限数组
          $rule_id = $this->getRule($act);                 //获取操作id
          if(in_array($rule_id,$rules_arr)){
            return 1;  
          }else{
            return 0;
          }
        }
      }
      //获取用户全部所属组
      protected function getGroups($uid){
         $map['uid'] = $uid;
         return M("AuthGroupUser")->where($map)->getField('group_ids');
      }
      
      //获取所有组权限总和
      protected function getGroupsRule($group_ids){
         $map['status'] = 1;
         $map['id'] = array('IN',$group_ids);
         $rules =  M("AuthGroup")->where($map)->field('rule_ids')->select();
         foreach($rules as $v){
            $rules_str.=$v['rule_ids'];
         }
         return array_unique(explode(',',$rules_str));
      }
      //获取操作id
      protected function getRule($act){
         $map['name'] = strtolower($act);
         return M("AuthRule")->where($map)->getField('id');
      }
//----------------------------------------------
}
