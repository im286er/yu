<?php
namespace Index\Controller;
use Think\Controller;
use Think\Verify;
use Index\Util\CartSession;
class LoginController extends CommonController {
  /*----------------登录头信息---------------------------*/
   private function _info() {
      if(isLogin()) {
         $data['status'] = true;
         $data['uid'] = session('uid');
         $data['user'] = session('user');
      }else{
         $data['status'] = false;
      }
      return $data;
   }
   /*---------------登录首页---------------------------*/
   public function index(){
      if(isLogin()){
        redirect(U('/'));
      }else{
        $this->page_title = '欢迎登录';
        $this->display();
      } 
   }
   /*---------------验证码----------------------------*/
   public function verify() {
      ob_end_clean();
      $config = array(
         'fontSize' => 80, // 验证码字体大小
         'length' => 4, // 验证码位数
         'useNoise' => false, // 关闭验证码杂点
         'useCurve' => false,
         );
      $Verify = new Verify($config);
      $Verify->entry();
   }
   /*---------------登录验证----------------------------*/
   public function doLogin(){
      $res = array();
      $map['mobile'] = I('item/s');
      $user = M('User');
      if($user->where($map)->count()) {
         $userinf = $user->where($map)->find();
         $pwd_check = validate_password(I('pwd'),$userinf['salt'],$userinf['hash']);
         if($pwd_check){
            D('User')->loginUpdate($userinf); //更新统计用户信息
            $res['code'] = 1;
            $res['jumpUrl'] = I('jumpUrl');
            $res['callback'] = I('callback');
         }else{
		 	$res['code'] = 0;
            $res['msg'] = '账号或密码错误';
		 }
      } else {
            $res['code'] = 0;
            $res['msg'] = '账号或密码错误';
      }
      $this->ajaxReturn($res);
   }
   /*-------------------------------------------*/
   public function isLogin() {
      $data = $this->_info();
      $this->ajaxReturn($data);
   }
   /*-------------------------------------------*/
   public function info(){
      //登录信息
      $this->assign('login_info',$this->_info());
      //用户未读私信
      $this->assign('unread_priMsg_num',priMsgUnread());
      //购物车信息
      $cart = new CartSession();
      $this->assign('cart_num',$cart->amount());
      $this->display();
   }
   /*------------------找回密码-----------------*/
   public function retrievePassword(){
        $this->page_title = '找回密码';
        $this->display();
   }
   /*-----------找回密码手机验证码发送----------*/
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
   /*-----------修改密码------------------------*/
   public function changePassword(){
        if(I('sms_code')!=session('sms_code')) $this->yktError('缺少手机验证码或验证码已过期,请重新获取手机验证码!');
        $user = M("User"); 
        $rules = array(array('mobile','require','请填写手机!'),
                       array('password','require','请填写密码!'),
                       array('password','checkPassword','密码至少6位!，且包含字母数字',1,'function',3),
                       array('confirm_password','password','确认密码不正确',0,'confirm'), 
                 );
        if (!$user->validate($rules)->create()) {
              $this->yktError('更改密码失败,请重试');
        } else {
            $map['mobile'] = I('mobile');
            $new_hash =  create_hash(I('password'));
            $data['salt'] = $new_hash['salt'];
            $data['hash'] = $new_hash['hash'];
            if ($user->where($map)->save($data)){
                $this->yktSuccess('修改密码成功,请重新登录!',U('Index/Login/index'),5);
            } else {
                $this->yktError('更改密码失败,请重试');
            }
        }
   }
//--------------------------------------------
}

?>