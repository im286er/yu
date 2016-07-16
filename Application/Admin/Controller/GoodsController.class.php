<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;
use Think\Upload;
use Think\Image;
use Think\Storage\Driver\File;
class GoodsController extends CommonController {
   /**
     * 搜索区 
     * -------------------------------------
     * 
     */
   private function allStatusSearch($map){  
        if(IS_POST){
             if(I('goods_name'))  $map['goods_name'] = array('like','%'.I('goods_name').'%');
             if(I('status'))      $map['status'] = I('status');
             if(I('cat_id'))      $map['cat_id'] = I('cat_id');
        }
        $this->assign('catlist',D('Category')->catList()); 
        $this->mapSearch('Goods',$map,$orderby='','id,goods_name,goods_img,cat_id,status,add_time');
   }
   //----------------------在售商品----------------------------
   public function onsale(){
      $map['on_sale']  = 1;  
      $map['pre_sale']  = 0;    
      $this->allStatusSearch($map);
      $this->display();
   }
   //----------------------下架商品----------------------------
   public function offsale() {
      $map['on_sale']  = 0;    
      $this->allStatusSearch($map);
      $this->display();
   } 
   //----------------------预告商品----------------------------
   public function presale() {
      $map['on_sale']  = 1; 
      $map['pre_sale']  = 1;    
      $this->allStatusSearch($map);
      $this->display();
   } 
   //----------------------库存提醒---------------------------
   public function stock() {
      $type = 1;
      if(IS_POST){
             if(I('type'))  $type = I('type');
      }
      if($type == 1){
        $map['stock_rate'] = array('ELT',0.2);
        $map['stock'] = array('GT',0);
      }else{
        $map['stock'] = 0;
      }
      $map['status'] = 1;
      $this->mapSearch('GoodsPrivate',$map);
      $this->display();
   }
   /**
     * 显示区
     * -------------------------------------
     * 
    */
    //----------------套装信息----------------------------------
    public function attrInfo() {
      $map['id'] = I('get.gid/d');
      $goodsinf = D('Goods')->relation('private')->where($map)->find();
      $this->assign('vo',$goodsinf);
      $this->display('Goods:attr_info');
    } 
    //------------------------------
    public function attr_form(){
       $attr_des = I('attr_des');
       $this->assign('attr_des',$attr_des); 
       if(I('multi')==1){
            $this->display('Goods:attr_form_single'); 
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
            $this->display('Goods:attr_form_double'); 
       }else{
            return false;
       } 
     }
     //-------------------公有属性选项卡-------------------------------
     public function publicAttrItem(){
      $this->assign('list',D('PublicAttr')->chooseItem(I('cat_id/d')));
      $this->display('Goods:public_attr_item');
     }
     /**
     * 操作区
     * -------------------------------------
     * 
     */
   //--------------------------------------------------
     public function add(){      
          $this->assign('catlist',D('Category')->catList());
          $cat_id = 4;
          if(I('cid')){
            $con_inf =  M('Consign')->find(I('cid'));
            $cat_id = $con_inf['cat_id'];
            $attr_des = unserialize($con_inf['attr_des']);
            $attr_val = unserialize($con_inf['attr_val']);
            $this->assign('attr_des',$attr_des);
            $this->assign('attr_val',$attr_val);
            $this->assign('con_inf',$con_inf);
            $this->assign('cid',I('cid'));
          }
          $this->assign('cat_id',$cat_id);
          $this->display();
     }
    //--------------------------------------------------
       public function insert() {
          $goods = D('Goods'); 
          if (!$goods->create()){  
              $this->error($goods->getError());
          }else{
              M()->startTrans(); //开始事务
              //-------------------------------
              $gid=$goods->add();
              $gp_ids = D('GoodsPrivate')->addItem(I('multi'),$gid);
                 if($gid && $gp_ids){
                    M()->commit();
                    if(I('tag')){    //添加标签
                        D('GoodsTagMap')->addMap(D('GoodsTag')->mapArr(I('tag'),$gid));
                    } 
                    if(I('cid')>0){  //修改寄售记录
                        $cs = D('Consign');$data['id'] = I('cid');$data['gid'] = $gid;$data['status'] = C('CONSIGN_STATUS_VAL.turn_goods');
                        $cs->statusNotify(get_uid(I('c_user')),I('goods_name'),7,I('cid'))->save($data);
                    } 
                    S('goods_detail_'.$gid,NULL);
                    $this->success('商品添加成功!');
                 }else{
                    M()->rollback();
                    $this->error('商品添加失败!');
                 }
              //-------------------------------
          } 
       }
      //--------------------------------------------------
       public function edit(){
          $this->assign('catlist',D('Category')->catList());
          $goodsinf =  D('Goods')->relation(true)->find(I('id'));
          $this->assign('vo',$goodsinf);
          $this->display();
       }
      //--------------------------------------------------
       public function update() {   
          $goods = D('Goods');
          if (!$goods->create()){  
              $this->error($goods->getError());
          }else{
              if($goods->save()){
                 $gid = I('id');
                 D('GoodsPrivate')->updateItem(I('multi'),$gid);
                 if(I('tag')){
                    D('GoodsTagMap')->delMap($gid)->addMap(D('GoodsTag')->mapArr(I('tag'),I('id')));
                 }else{
                    D('GoodsTagMap')->delMap($gid);
                 }
                 S('goods_detail_'.$gid,NULL);
                 $this->success('商品修改成功!');
              } else {
                 $this->error('商品修改失败!');
              }
          }
       }  
       //--------------------------------------------------
       public function foreverdelete() {   
           $ids = I('id/s');
           M()->startTrans(); //开始事务
           $map['id'] = array('IN',$ids);
           $mapp['gid'] = array('IN',$ids);
           M('Goods')->where($map)->delete();
           M('GoodsPrivate')->where($mapp)->delete();
           M('GoodsTagMap')->where($mapp)->delete();
           M()->commit();
           $this->success('商品删除成功!');
       }  
      /**
     * 上架区 
     * -------------------------------------
     * 
     */   
     //--------------------------------------------------
     public function doOffSale() {  
        $ids = I('id/s');
        M()->startTrans(); //开始事务
        $map['id'] = array('IN',$ids);
        $mapp['gid'] = array('IN',$ids);
        M('Goods')->where($map)->save(array('on_sale'=>0));
        M('GoodsPrivate')->where($mapp)->save(array('status'=>0));
        M()->commit();
        $this->success('商品下架成功!');
     }    
    //--------------------------------------------------
     public function doOnSale() {   
        $ids = I('id/s');
        M()->startTrans(); //开始事务
        $map['id'] = array('IN',$ids);
        $mapp['gid'] = array('IN',$ids);
        M('Goods')->where($map)->save(array('on_sale'=>1));
        M('GoodsPrivate')->where($mapp)->save(array('status'=>1)); 
        M()->commit();
        $this->success('商品上架成功!');
     } 
      /**
     * 推荐区 
     * -------------------------------------
     * 
     */   
     //--------------------------------------------------
     public function doPromote() {  
        if(I('get.cat_id/d') && I('get.gid/d')){
            $cat_id = I('get.cat_id/d');
            $gid = I('get.gid/d');
            $goods_detail = M('Goods')->field('id,goods_name,goods_img,status,original')->find($gid);
            $promote_arr = empty(F('goods_promote_cat_'.$cat_id))?array():F('goods_promote_cat_'.$cat_id);
            //-----------------------
            $goods_exist = false;
            for($i=0;$i<20;$i++){
                if($promote_arr[$i]['id']==$gid){
                  $goods_exist=true;
                  break;  
                } 
            }
            if(!$goods_exist){
                array_unshift($promote_arr,$goods_detail);
                if(count($promote_arr) > 25) array_pop($promote_arr);
                F('goods_promote_cat_'.$cat_id,$promote_arr);
                echo '推荐成功! ✔';
            }else{
                echo '已推荐! ✔';
            }
        }else{
            $ids = I('id/s');
            $map['id'] = array('IN',$ids);
            $goods_arr = M('Goods')->field('id,goods_name,goods_img,status,original')->where($map)->select();
            $promote_arr = empty(F('goods_promote_main'))?array():F('goods_promote_main');
            foreach($goods_arr as $k=>$v){
                array_unshift($promote_arr,$v);
            }
            $promote_arr = array_slice($promote_arr,0,25);
            F('goods_promote_main',$promote_arr);
            $this->success('商品推荐首页成功!');
        }
     }    
     //--------------------------------------------------
//--------------------------------------------------
}
?>