<?php
namespace Index\Controller;
use Think\Controller;
class RegistController extends CommonController{
    //---------------------已登录跳转
    protected function _initialize(){
	   if(session('uid')){
	      redirect('/'); 
       }
	}
    //---------------------
    public function index(){   
       $this->display(); 
    }
    //---------------------发送手机验证码
    public function sendSMSCode(){
       $mobile = I('mobile/s');
       if($mobile){
           $code = rand(10000,99999);	
           session('sms_code',$code);
           sendSMS($mobile,$code);
	   	   $res['code'] = 1;
           $res['msg'] = '验证码已发送到你的手机了!';
	   }else{
	   	 $res['code'] = 0;
         $res['msg'] = '手机号不能识别';
	   }
       $this->ajaxReturn($res);
    }
    //---------------------检测手机验证码
    public function checkSMSCode(){
       if(IS_POST && IS_AJAX){
          if(I('sms_code')==session('sms_code')){
            echo 'true'; 
          }else{
            echo 'false';
          }
       }
    }
    //---------------------判断唯一性
    public function checkUserItemUnique(){
        if(I('mobile'))   $map['mobile'] = I('mobile');
        if(I('username')) $map['username'] = I('username');
        if(empty($map)) return false;
        if (IS_POST && IS_AJAX) {
            $count = M('User')->where($map)->count();
            if ($count > 0) {
                echo 'false';
            } else {
                echo 'true';
            }
        } 
    }
    //---------------------
    public function regUser(){
    	if(I('sms_code')!=session('sms_code')) $this->yktError('缺少手机验证码或验证码已过期,请重新获取手机验证码!');
        $user = M("User"); 
        $rules = array(array('mobile','require','请填写手机!'),
                       array('mobile','','手机号码已经存在！',1,'unique',3),
                       array('username','require','请填写用户名!'),
                       array('username','','帐号名称已经存在！',1,'unique',3),
                       array('password','require','请填写密码!'),
                       array('password','checkPassword','密码至少6位!，且包含字母数字',1,'function',3),
                       array('confirm_password','password','确认密码不正确',0,'confirm'), 
                       array('agree','1','请同意御咖塘使用协议',1,'equal',3), 
                 );
        if (!$user->validate($rules)->create()) {
              //$this->error($user->getError());
              $this->yktError('注册失败，请重试');
        } else {
            $data['username'] = I('username');
            $data['mobile'] = I('mobile');
            $data['sex'] = I('sex',1,int);
            $new_hash =  create_hash(I('password'));
            $data['salt'] = $new_hash['salt'];
            $data['hash'] = $new_hash['hash'];
            $data['reg_time'] = $data['last_login'] = time();
            $data['last_ip']  = $_SERVER["REMOTE_ADDR"];
            $uid = $user->add($data); //添加
            if ($uid) {
                session('user', I('username'));
                session('uid', $uid);
                session('last_login',time());
                $this->yktSuccess('注册成功，欢迎来到御咖塘','/',2);
            } else {
                $this->yktError('注册失败，请重试');
            }
        }
    }
//--------------------------------------------------------
}

?>