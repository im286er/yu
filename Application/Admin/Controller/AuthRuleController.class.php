<?php
namespace Admin\Controller;
use Think\Controller;
//use Common\Util\TableTree;
//use Common\Util\Auth;
class AuthRuleController extends CommonController {
   //---------------------------------- 
   public function index() {
      $this->list = D('AuthRule')->getRuleList();
      $this->display();
   }
   //----------------------------------
   public function add() {
      $this->list = D('AuthRule')->getRuleList();
      $this->display();
   }
   //----------------------------------
   public function insert() {
    $authrule = M('AuthRule');
	if($authrule->create()){
		if($authrule->add()){
			$this->success('规则添加成功！');
		}else {
			$this->error('规则添加失败！');
		}
	}else {
		$this->error($authrule->getError());
	}
   }
   //----------------------------------
   public function edit() {
      $this->list = D('AuthRule')->getRuleList();
      $this->vo = M('AuthRule')->find(I('id'));
      $this->display();
   }
   //----------------------------------
   public function update() {
    $authrule = M('AuthRule');
	if($authrule->create()){
		if($authrule->save()){
			$this->success('规则编辑成功！');
		}else {
			$this->error('规则编辑失败！');
		}
	}else {
		$this->error($authrule->getError());
	}
   }
//------------------------------------------
}

?>