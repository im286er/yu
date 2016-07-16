<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends CommonController {
   //-----------------------------------------
   public function index() {
        if(IS_POST){
          if(I('keytype') != '') {
             $map[I('keytype')] = array('like','%'.I('keyword').'%'); //此处单独写模糊查询
          }
          if(I('status')!='')        $map['status'] = I('status');
          if(I('consigner')!='')     $map['consigner'] = I('consigner');
        }
        $this->mapSearch('User',$map);
        $this->display();
   }
   //-----------------------------------------
   public function choose(){
        if(IS_POST){
          if(I('keytype') != '') {
             $map[I('keytype')] = array('like','%'.I('keyword').'%'); //此处单独写模糊查询
          }
          if(I('status')!='')        $map['status'] = I('status');
          if(I('consigner')!='')     $map['consigner'] = I('consigner');
        }
        $this->mapSearch('User',$map);
        $this->display();
   }
   //-----------------------------------------
   public function edit(){ 
      $this->vo = M('User')->find(I('id'));
      $this->display();
   }
   //-----------------------------------------
   public function update() {
      $user = D('User');
      if (!$user->create()){  
          $this->error($user->getError());
      }else{
          if($user->save()){
             $this->success('用户修改成功!');
          } else {
             $this->error('用户修改失败!');
          }
      }
   }
   //-----------------------------------------
   public function detail() {
      $this->assign('vo',M('User')->find(I('uid')));
      $this->display();
   }
//------------------------------------------------------
}

?>