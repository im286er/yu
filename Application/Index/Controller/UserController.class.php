<?php
namespace Index\Controller;
use Think\Controller;
class UserController extends CommonController {
   //------------------------------------------
   protected function _initialize() {
      if(!isLogin()) {
         redirect(U('Index/Login/index'));
         return false;
      }
   }
   //------------------密码修改提交
   public function pwUpdate(){
        //验证新密码
        if(I('new_pw/s')!=I('renew_pw/s')){
            $msg['code'] = 0;
            $msg['msg'] = '两次新密码不一致';
            $this->ajaxReturn($msg);return false;
        }
        //验证原密码,手机号码
        $map['id'] = session('uid');
        $map['mobile'] = I('mobile/s');
        $userinf = M('User')->where($map)->field('mobile,salt,hash')->find(); 
        if(empty($userinfo['mobile'])){
            $msg['code'] = 0;
            $msg['msg'] = '手机号码错误';return false;
            $this->ajaxReturn($msg);  
        }else{
            $pwd_check = validate_password(I('init_pw'),$userinf['salt'],$userinf['hash']);
            if($pwd_check){
               $data['id'] = session('uid'); 
               $new_hash =  create_hash(I('new_pw'));
               $data['salt'] = $new_hash['salt'];
               $data['hash'] = $new_hash['hash'];
               M('User')->save($data); 
               $msg['code'] = 1;
               $this->ajaxReturn($msg); 
                
            }else{
                $msg['code'] = 0;
                $msg['msg'] = '原密码不正确';
                $this->ajaxReturn($msg);return false;
            }
        }
   }
   //------------------个人资料修改
   public function profileUpdate(){
        $data['sign'] = I('sign');$data['sex'] = I('sex');$data['birth'] = I('birth');
        $map['id'] = session('uid');
        if(M('User')->where($map)->save($data)){
            $msg['code'] = 1;
            $this->ajaxReturn($msg);
        }
   }
   //------------------实名认证
   public function doAuth(){
        $user = M("User"); 
        $rules = array(array('realname','require','请填写真名!'),
                       array('alipay_account','require','请填写支付宝账户!'),
                       array('alipay_user','require','请填写支付宝用户!'),
                 );
        if (!$user->validate($rules)->create()){
              pr($user->getError());
              return false;
        } else {
            $data['realname'] = I('realname');
            $data['alipay_account'] = I('alipay_account');
            $data['alipay_user'] = I('alipay_user');
            $data['consigner'] = 2;
            $map['id'] = session('uid');
            if($user->where($map)->save($data)){
                $msg['code'] = 1;
                $this->ajaxReturn($msg);
            }
        }
   }
//-------------------------------------------------------------------------
}

?>