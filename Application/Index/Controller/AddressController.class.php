<?php
namespace Index\Controller;
use Think\Controller;
class AddressController extends CommonController{
    //------------------------------------
    private $dp = 19;  //默认省份id，19广东省
    private $dc = 3258;  //默认城市id，3258广州市
    protected function _initialize(){
	   if(!isLogin()){
          exit('no login');
       }
	}
    //-----------------获取全部省份
    private function _getProvince(){
        return M('Area')->where('pid = 0')->order('sort desc')->select();
    }
    //-----------------获取下级地区
    private function _getSubArea($pid){
        return M('Area')->where('pid='.$pid)->select();
    }
    //-----------------获取当前用户已有地址数 
    private function _amount(){
        $map['uid'] = session('uid');
        return M('Address')->where($map)->count();
    }
    //------------------获取下级地区
    public function subArea(){       
      if(IS_AJAX){
        $sub = $this->_getSubArea(I('pid'));
        $this->ajaxReturn($sub);
      }
    }
    //------------------
    public function add(){
        $this->assign('province',$this->_getProvince());
        $this->assign('city',$this->_getSubArea($this->dp));
        $this->assign('district',$this->_getSubArea($this->dc));
        $this->display();
    }
    //------------------
    public function insert(){
         if(IS_AJAX){
              $ad = D('Address');  
              if (!$ad->create()){  
                  $this->error($ad->getError());
              }else{
                   $amount = $this->_amount();
                   if($amount==0)$ad->default = 1; //判断是否第一次新增
                   if($amount>=5){
                      $data['amount'] = $amount;
                   }else{
                      $new_id = $ad->add();
                      $data['id'] = $new_id;
                      $data['amount'] = $this->_amount();
                   }
                   $this->ajaxReturn($data);
              }   
         }     
    }
    //------------------
    public function edit(){
        $map['id'] = I('get.id/d');
        $map['uid'] = session('uid');
        if(M('Address')->where($map)->count()){
           $vo = M('Address')->where($map)->find();
           $this->assign('province',$this->_getProvince());
           $this->assign('city',$this->_getSubArea($vo['province']));
           $this->assign('district',$this->_getSubArea($vo['city']));
           $this->assign('vo',$vo);
        }
        $this->display();
    }
    //------------------
    public function update(){
        if(IS_AJAX){
          $ad = D('Address');  
          if (!$ad->create()){  
              $this->error($ad->getError());
          }else{
               if($ad->save()){
                  $data['code'] = 1;
               } else {
                  $data['code'] = 0;
               }  
          } 
          $this->ajaxReturn($data);  
        }   
    }
    //------------------
    public function del(){
      if(IS_AJAX){
          $map['uid'] = session('uid');
          $map['id'] = I('id');
          M('Address')->where($map)->delete();
      }
    }
    //------------------
    public function setDefault(){
      if(IS_AJAX){
          $map['uid'] = session('uid');
          $data['default'] = 0;
          M('Address')->where($map)->save($data);
          //----------------------
          $map['id'] = I('id',0,int);
          $data['default'] = 1;
          M('Address')->where($map)->save($data);
      }
    }
//----------------------------------------------
}

?>