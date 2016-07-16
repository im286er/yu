<?php
namespace Admin\Controller;
use Think\Controller;
class PublicAttrController extends CommonController
{
    //---------------------------------------
	public function index(){
        $cat_id = I('cat_id')?I('cat_id'):4;
		$this->list=D('PublicAttr')->getAttrList($cat_id);
		$this->assign('cat_id',$cat_id);
        $this->assign('catlist',D('Category')->catList());
		$this->display();
	}
	//---------------------------------------
	public function add(){
		$this->assign('catlist',D('Category')->catList());
		$this->display();
	}
	//---------------------------------------
	public function edit(){
        $this->assign('catlist',D('Category')->catList());
        $attr = D('PublicAttr')->where('id='.I('id'))->relation('cat')->find();
        $this->attr = $attr;
		$this->attrlist = D('PublicAttr')->getParentAttrList($attr['cat_id']);
		$this->pid = $attr['pid'];
        $this->cat_id = $attr['cat_id'];
        $this->cat_name = $attr['cat_name'];
		$this->display();
	}
	//---------------------------------------
    public function returnAttr(){
    	$PublicAttr = M('PublicAttr');
    	$where['cat_id'] = $_REQUEST['cat_id'];
    	$where['pid'] = 0;
    	$list = $PublicAttr->where($where)->field('id,title')->select();
    	$result = Array();
    	$result[] = array('0','顶层');
    	foreach($list as $v){
    		$result[] = array($v['id'],$v['title']);
    	}
    	echo json_encode($result);
    }
	//---------------------------------------
	public function insert(){
		$PublicAttr = M('PublicAttr');
		if($PublicAttr->create()){
			if($PublicAttr->add()){
				$this->success('公共属性添加成功！');
			}else {
				$this->error('公共属性添加失败！');
			}
		}else {
			$this->error($PublicAttr->getError());
		}
	}
	//---------------------------------------
	public function update(){
		$PublicAttr = M('PublicAttr');
		if($PublicAttr->create()){
			if($PublicAttr->save()){
				$this->success('公共属性编辑成功！');
			}else {
				$this->error('公共属性编辑失败！');
			}
		}else {
			$this->error($PublicAttr->getError());
		}
	}
//-------------------------------------------------------
}

?>