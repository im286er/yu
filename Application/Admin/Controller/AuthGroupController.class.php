<?php
namespace Admin\Controller;
use Think\Controller;
class AuthGroupController extends CommonController{
    //-----------------------------------------
    public function index(){
        if(IS_POST){
          if(I('title'))       $map['title'] = array('like','%'.I('title').'%');
        }
        $this->mapSearch('AuthGroup',$map);
        $this->display();
    }
    //-----------------------------------------
    public function add(){ 
        $this->display();
    }
	//-----------------------------------------
	public function insert(){  
      $group = D('AuthGroup');
      if (!$group->create()){  
          $this->error($group->getError());
      }else{
          $group_id=$group->add();
          if($group_id){
             $this->success('管理组添加成功!');
          } else {
             $this->error('管理组添加失败!');
          } 
      } 
	}
   //-----------------------------------------
   public function edit() {
      $group_inf = M('AuthGroup')->find(I('id'));
      $this->vo = $group_inf;
      $this->display();
   }
   //-----------------------------------------
   public function update() {
      $group = D('AuthGroup');
      if (!$group->create()){  
          $this->error($group->getError());
      }else{
          if($group->save()){
             $this->success('管理组编辑成功!');
          } else {
             $this->error('管理组编辑失败!');
          } 
      } 
   }
   //------------------------------------------- 
   public function searchRule(){
      $list = M('AuthRule')->order('sort')->select();
      $this->node = $this->list_to_tree($list);
      $this->display();
   } 
   //-----------------------------------------
   public function list_to_tree($list){
		foreach ($list as $key => $data){
			$refer[$data['id']]=& $list[$key]; //& 为引用，取出list[$key]的内存地址
		}
		foreach  ($list as $key => $data){
			$pid=$data['pid'];
			if($pid==0){
				$tree[]=& $list[$key]; //没有父节点的情况，取出第一层，放入$tree
			}else {
				if(isset($refer[$pid])){
					//重点难点。有父节点为二三层，实现的效果就是将其加入父节点之下
					$parent = & $refer[$pid]; //将其父节点内存地址赋予一个变量名称
					$parent['_child'][]=& $list[$key]; //在刚刚赋予的变量名称里加入子数组_child
				}
			}
		}
		return $tree;
	}
//----------------------------------
}

?>