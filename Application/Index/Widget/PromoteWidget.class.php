<?php
namespace Index\Widget;
use Think\Controller;
class PromoteWidget extends Controller{   
    //---------------------------------------
	public function show($controller,$block_sign,$limit){
         $map['sign'] = $block_sign;
         $map['pid'] =  M('PromoteBlock')->where("sign = '$controller'")->getField('id');
         $bid =  M('PromoteBlock')->where($map)->getField('id');
         $list = M('Promote')->cache('Promote_'.$controller.'_'.$block_sign,60*60)
                             ->where('bid='.$bid)
                             ->order('sort ASC,sorttime DESC,id DESC')
                             ->limit($limit)->select();
         $this->assign('block_sign',$block_sign);
         $this->assign('list',$list);
         $this->display('Widget:Promote:'.$controller);
	}
//---------------------------------------
}
?>