<?php
namespace Index\Controller;
use Think\Controller;
class GoodsController extends CommonController {
   //-------------------------------商品显示
   public function item() {
      //-----获取商品信息
      C('SHOW_PAGE_TRACE',0);
      $gid = I('get.id/d'); $attr = I('get.attr/s');  
      if($gid < 0) return false;
      $goods_inf = D('Goods')->baseInfo($gid);               //商品基本信息
      if($goods_inf['on_sale'] == 1){ 
           $gp = D('GoodsPrivate')->gpInfo($gid,$attr);      //商品扩展信息
           $this->assign('goods_inf',$goods_inf);
           $this->assign('gp',$gp);
           $this->assign('status',C('GOODS_STATUS'));
           $this->page_title = $goods_inf['goods_name'].'_'.$goods_inf['cat_name'].'_通贩区';
           $this->display();
      }else{
          $this->display('Goods:none');
      }
   }
   //-------------------------------收藏/取消收藏 商品
   public function doCollect() {      
        if(IS_AJAX && session('uid') && I('gid/d')){
            $gc = M('GoodsCollect'); $gid = I('gid/d'); $cat_id = I('cat_id/d');
            //----------------
            if(I('type')=='+'){
                $map['gid'] = $gid;$map['cat_id'] = $cat_id;$map['uid'] = session('uid'); 
                if($gc->where($map)->count()){ //已经收藏过了!
                    $msg['code'] = 2;
                }else{
                    $data = array('uid'=>session('uid'),'cat_id'=>$cat_id,'gid'=>$gid);
                    if($gc->add($data)){  
                       M('Goods')->where('id='.$gid)->setInc('collect_num',1);
                    }
                    $msg['code'] = 1; 
                }
            }else if(I('type')=='-'){
                $map['uid'] = session('uid');
                $map['gid'] = $gid;
                $gc->where($map)->delete();
                $msg['code'] = 1;
            }else{
                $msg['code'] = '-1';
            }
            $this->ajaxReturn($msg);
        }
   }
   //-------------------------------点赞商品
   public function doLike() {      
        if(IS_AJAX){
            $gid = I('get.gid/d'); if($gid <= 0) return false;
            M('Goods')->where('id='.$gid)->setInc('like_num',1);
        }
   }
   //-------------------------------访问数加1
   public function doView(){  
     if(IS_AJAX){
         $gid = I('get.gid/d'); if($gid <= 0) return false;
         //-----------------------------商品访问数加1
         M('Goods')->where('id='.$gid)->setInc('views',1);
         //-----------------------------浏览商品cookie
         $scan_goods = cookie('scan_goods');
         if(empty($scan_goods)) $scan_goods = array();
         if($scan_goods[0]==$gid) return false;
         array_unshift($scan_goods,$gid);
         cookie('scan_goods', array_slice(array_unique($scan_goods),0,36));
     }
   }
//------------------------------------
}

?>