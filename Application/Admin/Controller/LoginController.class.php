<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Verify;
class LoginController extends CommonController{
    //--------------------
    protected function _initialize() {
      if(session('uid') && session('admin')) {
         redirect('/Admin/');
      }
    }
    //--------------------
    public function index(){
        $this->display();
    }
    //--------------------
    public function verify() {
      ob_end_clean();
      $config = array(
         'fontSize' => 100, // 验证码字体大小
         'length' => 4, // 验证码位数
         'useNoise' => false, // 关闭验证码杂点
         'useCurve' => false,
         );
      $verify = new Verify($config);
      $verify->entry();
   }
   
   public function backstageLogin() {  
      //-----------------------------------验证码判断
      $verify = new Verify();
      if(!$verify->check(I('code'))){
        $this->error('验证码错误!');
      }else{
        $map['email'] = I('email');
        if(M('User')->where($map)->count()){                                            //判断用户是否存在
            $userinf = M('User')->where($map)->find();
            $pwd_check = validate_password(I('pwd'),$userinf['salt'],$userinf['hash']);
            if($pwd_check){                                          //密码正确    
                $groupinf = M('AuthGroupUser')->where('uid='.$userinf['id'])->field('uid')->find(); //判断是否管理组
                if($groupinf['uid']){
                    session('user',$userinf['username']);
                    session('uid',$userinf['id']);
                    session('admin',true);
                    //--------------------------------
                    $this->success('登录成功，欢迎来到御咖塘管理后台','/Admin/',1);
                }else{
                   $this->error('账号或密码错误!'); return false; 
                }
            }else{
                $this->error('账号或密码错误!'); return false;
            }
        }else{
            $this->error('账号或密码错误!'); return false; 
        }
      }  
   }
//----------------------------------------------------------------------------
}
?>