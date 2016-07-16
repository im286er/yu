<?php
namespace Admin\Controller;
use Think\Controller;
class AuthGroupUserController extends CommonController {
   //-----------------------------------------
   public function index() {
        if(IS_POST){
          if(I('uid')!='')           $map['uid'] = I('uid');
          if(I('username')!='')      $map['uid'] = get_uid(I('username'));
        }
        $this->mapSearch('AuthGroupUser',$map);
        $this->display();
   }
   //-----------------------------------------
   public function add(){ 
      $this->display();
   }
   //-----------------------------------------
   public function insert(){ 
      $guser = D('AuthGroupUser');
      //------------------------------------------------------  
      if (!$guser->create()){  
          $this->error($guser->getError());
      }else{
          $guser_id=$guser->add();
          if($guser_id){
             $this->success('管理用户添加成功!');
          } else {
             $this->error('管理用户添加失败!');
          } 
      } 
   }
   //-----------------------------------------
   public function edit() {
      $guser_inf = M('AuthGroupUser')->find(I('id'));
      $guser_inf['group_names'] = groupName($guser_inf['group_ids']);
      $this->vo = $guser_inf;
      $this->display();
   }
   //-----------------------------------------
   public function update() {
      $guser = D('AuthGroupUser');
      if (!$guser->create()){  
          $this->error($guser->getError());
      }else{
          if($guser->save()){
             $this->success('管理用户编辑成功!');
          } else {
             $this->error('管理用户编辑失败!');
          } 
      } 
   }
   //-----------------------------------------
   public function searchGroup() {
      $authgroup = M('AuthGroup');
      $this->grouplist = $authgroup->select();
      $this->display();
   }
//------------------------------------------------------
}

?>