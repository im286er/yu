<?php
namespace Index\Controller;
use Think\Controller;
class ConsignController extends CommonController {  
    //检查寄售资格
    protected function _initialize(){                                 
        if(!isLogin()){
          redirect(U('Index/Login/index'));
          return false;
        }else{
          $consigner = M('User')->where('id='.session('uid'))->getField('consigner');
          if($consigner == C('CONSIGN_QUALI_VAL.common')){
             
             $link[0] = array('text'=>'现在去实名认证','link'=>U('Index/Home/auth'));
             $this->yktSuccess('请先到个人中心通过实名认证','','',$link);
          
          }else if($consigner == C('CONSIGN_QUALI_VAL.apply')){
             
             $link[0] = array('text'=>'回到首页','link'=>U('/'));
             $this->yktSuccess('你的申请正在审核中,请耐心等候','','',$link);
          
          }else if($consigner == C('CONSIGN_QUALI_VAL.refuse')){
             $link[0] = array('text'=>'寄售申请未通过','link'=>U('/'));
             $this->yktError('你的寄售申请未通过,请联系网站管理员','','',$link);
          }
        }
    } 
   //------------------------------单双配搭表格[修改/详细]
   private function attr_form_multi($attr_des,$attr_val,$multi){
       $attr_fill = array();
       foreach($attr_val as $k=>$v){
            $attr_fill[$v['attr']]['price'] = $v['price'];
            $attr_fill[$v['attr']]['num'] =   $v['num'];
            $attr_fill[$v['attr']]['img'] =   $v['img'];
       }
       $this->assign('attr_des',$attr_des);    //分配属性描述
       $this->assign('attr_fill',$attr_fill);  //分配已填表格
       if($multi==1){
          return  $this->fetch('Consign:attr_form_single'); 
       }else if($multi==2){
            $attr_match = array();
            $attr_num[1] = count($attr_des['1']['val']);
            $attr_num[2] = count($attr_des['2']['val']);
            $i = 1;
            //循环第一属性
            for($n=1;$n<=$attr_num[1];$n++){
              //循环第二属性
                  for($m=1;$m<=$attr_num[2];$m++){
                    $attr_match[$i]['attr'] = $n.'-'.$m;
                    $attr_match[$i]['attr_match'] = $attr_des['1']['val'][$n].'-'.$attr_des['2']['val'][$m];
                    $attr_match[$i]['attr_str'][1] = $attr_des['1']['val'][$n];
                    $attr_match[$i]['attr_str'][2] = $attr_des['2']['val'][$m];
                    $i++;
                  }  
            }
            $this->assign('attr_match',$attr_match);
            return $this->fetch('Consign:attr_form_double');
       }else{
        return false;
       }
   }
   //预寄售选择种类
   public function pre_apply(){
      $this->page_title = '申请寄售';
      $this->display();
   }
   //寄售页面
   public function apply(){
      $cat_id = I('get.cat/d');
      $consign_cat =  C('CONSIGN_CAT');
      if($consign_cat[$cat_id]){
         $this->assign('PublicAttr',D("PublicAttr")->subList($cat_id));
         $this->assign('consign_cat',$consign_cat);
         $this->assign('cat_id',$cat_id);
         $this->page_title = '申请寄售';
         $this->display();
      }else{
         $this->yktError('没有这个寄售分类!');
      }
   }
   //生成配搭表格
   public function attr_form(){
     $attr_des = I('attr_des');
     $attr_fill = I('attr_fill');
     $this->assign('attr_fill',$attr_fill);
     $this->assign('attr_des',$attr_des); 
     if(I('multi')==1){
        $this->display('Consign:attr_form_single'); 
     }else if(I('multi')==2){
        $attr_match = array();
        $attr_num[1] = count($attr_des['1']['val']);
        $attr_num[2] = count($attr_des['2']['val']);
        $i = 1;
        //循环第一属性
        for($n=1;$n<=$attr_num[1];$n++){
          //循环第二属性
              for($m=1;$m<=$attr_num[2];$m++){
                $attr_match[$i]['attr'] = $n.'-'.$m;
                $attr_match[$i]['attr_match'] = $attr_des['1']['val'][$n].'-'.$attr_des['2']['val'][$m];
                $attr_match[$i]['attr_str'][1] = $attr_des['1']['val'][$n];
                $attr_match[$i]['attr_str'][2] = $attr_des['2']['val'][$m];
                $i++;
              }  
        }
        $this->assign('attr_match',$attr_match);
        $this->display('Consign:attr_form_double'); 
     }else{
        return false;
     } 
   }
   //提交寄售
   public function doApply(){
      $consign = D('Consign');
      if (!$consign->create()){  
          $this->error($consign->getError());
      }else{
          $new_id=$consign->add(); 
          if($new_id){ 
              $link[0] = array('text'=>'继续寄售','link'=>U('Index/Consign/apply',array('cat'=>I('cat_id'))));
              $link[1] = array('text'=>'查看寄售','link'=>U('Index/Home/consign'));
              $this->yktSuccess('感谢您的寄售申请！我们将会在48小时内处理~消息会通过站内信通知您~','','',$link);
          } else {
             $this->yktError('寄售申请失败，请仔细核对寄售资料!');
          } 
      } 
   }
   //寄售详细
   public function detail(){
      $cid = I('get.cid/d');
      $consign_inf = D('Consign')->relation('cat')->find($cid);
      if($consign_inf['uid']==session('uid')){  //判断属于当前用户
         $multi = $consign_inf['multi'];
         $attr_val = unserialize($consign_inf['attr_val']);
         if($multi > 0){
             $attr_des = unserialize($consign_inf['attr_des']);
             $this->assign('attr_des',$attr_des);
             $this->assign('attr_form',$this->attr_form_multi($attr_des,$attr_val,$multi));
         }else{
            $this->assign('attr_val',$attr_val);
         } 
         $this->assign('consign_inf',$consign_inf);
         $this->assign('PublicAttr',D("PublicAttr")->subList($consign_inf['cat_id']));
         $this->page_title = '寄售详情';
         $this->display();
      }else{
        $this->yktError('获取寄售信息失败');
        return false;
      }
   }
   //寄售编辑
   public function edit(){
      $cid = I('get.cid/d');
      $consign_inf = M('Consign')->find($cid);
      if($consign_inf['uid']==session('uid') && $consign_inf['status']==2){ 
            $multi = $consign_inf['multi'];
            $attr_val = unserialize($consign_inf['attr_val']);
            if($multi > 0){
                 $attr_des = unserialize($consign_inf['attr_des']);
                 $this->assign('attr_des',$attr_des);
                 $this->assign('attr_form',$this->attr_form_multi($attr_des,$attr_val,$multi));
            }else{
                $this->assign('attr_val',$attr_val);
            }
           
           $this->assign('consign_inf',$consign_inf);
           $this->assign('PublicAttr',D("PublicAttr")->subList($consign_inf['cat_id']));
           $this->page_title = '寄售修改';
           $this->display();
      }else{
         $this->yktError('获取寄售信息失败');
      }
   }
   //寄售修改
   public function update(){
      $consign = D('Consign');
      if (!$consign->create()){  
          $this->error($consign->getError());
      }else{
          $consign->status = 3;
          if($consign->save()){ 
              $this->yktSuccess('寄售修改成功!',U('Index/Consign/detail',array('cid'=>I('id'))));
          } else {
             $this->yktError('寄售申请失败，请仔细核对寄售资料!');
          } 
      } 
   }
   //寄出快递
   public function expressFill(){
       $cid = I('cid');
       if($cid < 0) return false;
       $map['uid'] = session('uid');
       $map['status'] = C('CONSIGN_STATUS_VAL.pre_send');
       $consign_inf = M('Consign')->where($map)->find($cid);
       if($consign_inf['uid']){
            if(I('express_num') && I('express_abbr')){
                $data['id'] = $cid;
                $data['express_num'] = I('express_num');
                $data['express_abbr'] = I('express_abbr');
                $data['status'] = C('CONSIGN_STATUS_VAL.send');
                if(M('Consign')->save($data)){
                    $res['code'] = 1;
                }else{
                    $res['code'] = 0;
                    $res['msg'] = '寄售快递号更新失败!'; 
                }
            }
       }else{
            $res['code'] = 0;
            $res['msg'] = '寄售状态出错!';
        
       }
       $this->ajaxReturn($res);
   }
//-------------------------------------------------------------------------
}

?>