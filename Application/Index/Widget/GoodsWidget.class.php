<?php
namespace Index\Widget;
use Think\Controller;
class GoodsWidget extends Controller{   
    //---------------------------------------
	public function hot($cat_id){ 
          $map['cat_id'] = $cat_id;
          $order_by = '';
          switch(rand(1,4)) {
            case 1:
                $order_by = 'views';
                break;
            case 2:
                $order_by = 'comment_num';
                break;
            case 3:
                $order_by = 'collect_num';
                break;
            case 3:
                $order_by = 'like_num';
                break;
            case 4:
                $order_by = 'id';
                break;
            default:
                $order_by = 'id';
                break;
          }
          //------------------------------------
          $map['cat_id'] = $cat_id;
          $list = M('Goods')->cache('Goods_Hot_Cat_'.$cat_id.'_by_'.$order_by,60*60)
                            ->where($map)
                            ->field('id,goods_name,goods_img')
                            ->order($order_by.' DESC')->limit(10)->select();
          $this->assign('list',$list);
          $this->assign('cat_id',$cat_id);
          $this->display('Widget:Goods:hot');
	}
//---------------------------------------
}
?>