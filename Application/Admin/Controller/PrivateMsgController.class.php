<?php
namespace Admin\Controller;
use Think\Controller;
class PrivateMsgController extends CommonController{
   //---------------------------------
   public function index(){  
       $map = array();
       if(IS_POST){
         if(I('send_user'))      $map['send_uid'] = get_uid(I('send_user'));
         if(I('rec_user'))       $map['rec_uid'] = get_uid(I('rec_user'));
         if(I('title'))         $map['title'] = array('like','%'.I('title').'%');
       }
       $this->mapSearch('PrivateMsg',$map);
       $this->display();
   }
   //---------------------------------
   public function add() {
      $this->display();
   }
   //---------------------------------
   public function insert(){
      //���ر������
      if(I('user_uid')){
         $rece_arr = explode(',',I('user_uid'));
         foreach($rece_arr as $v){
            priMsgSend(session('uid'),$v,I('title'),I('summary'),I('link'),'Common','');
         }
         $this->success('������Ϣ�ɹ�!');
      }else{
        $this->error('δѡ������û�'); 
      }
   }
   //---------------------------------
   public function edit() {
      $this->vo =  M('PrivateMsg')->find(I('id'));
      $this->display();
   }
   //---------------------------------
   public function update() {
      $primsg = D('PrivateMsg');
      if($primsg->create()){      
          if($primsg->save()){ 
             $this->success('����༭�ɹ�!');
          } else {
             $this->error('����༭ʧ��!');
          }
      }else{
        $this->error($primsg->getError());  
      }
   }	
//----------------------------------------
}

?>