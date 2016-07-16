<?php
namespace Index\Controller;
use Think\Controller;
use Index\Util\CartSession;
class CartController extends CommonController{
    //-----------------------------购物车首页
    public function index(){
       $cart = new CartSession();
       $this->assign('cart',$cart->read());
       $this->assign('total',$cart->total_price());
       $this->assign('amount',$cart->amount());
       $this->display();
    }
    //------------------------------添加
    public function add(){
        $gid = I('gid/d');$gpid = I('gpid/d');$num = I('num/d');
        if(!goodsSaleAllow($gid)) $this->yktError('商品不可销售!');
        $price = gpidToField($gpid,$gid,'price');  //判断gid,gpid一致性同时，获取商品价格 
        if($gid>0 && $gpid>0 && $num>0 && $price>0){
              $cart = new CartSession();
              $res = $cart->add($gpid,$num,$price);
              if($res){
                  redirect(U('Index/Cart/success')); //跳去订单确定页面  
              }else{
                $this->yktError('添加购物车失败!'); 
                return false;
              }  
        }else{
           $this->yktError('商品参数错误!');  
           return false; 
        }
    }
    //------------------------------增加1
    public function increase(){ 
     if(IS_AJAX){   
          $gpid = I('gpid/d');
          if($gpid > 0){
               $cart = new CartSession();
               $res = $cart->increase($gpid);
               if($res){
                  $data['code'] = 1;
                  $data['item_price'] = $cart->item_price($gpid);
                  $data['total_price'] = $cart->total_price();
               }else{
                  $data['code'] = 0;
               }
               $this->ajaxReturn($data);
          } 
     }
    }
   //------------------------------减少1
   public function reduce(){ 
    if(IS_AJAX){
       $gpid = I('gpid/d'); 
       if($gpid > 0){
           $cart = new CartSession();
           $res = $cart->reduce(I('gpid/d'));
           if($res){
              $data['code'] = 1;
              $data['item_price'] = $cart->item_price($gpid);
              $data['total_price'] = $cart->total_price();
           }else{
              $data['code'] = 0;
           }
           $this->ajaxReturn($data);
       }
     }
   }
   //------------------------------删除单行
   public function remove(){ 
    if(IS_AJAX){
          $gpid = I('gpid/d');
          if($gpid > 0){
               $cart = new CartSession();
               $res = $cart->remove($gpid);
               if($res){
                  $data['code'] = 1;
                  $data['total_price'] = $cart->total_price();
                  $data['cart_num'] = $cart->amount();
               }else{
                  $data['code'] = 0;
                  $data['msg'] = '删除商品错误!';
               }
           $this->ajaxReturn($data);
          
          }     
     }
   } 
   //------------------------------设置单行数量
   public function adjust(){ 
        if(IS_AJAX){
              $gpid = I('gpid/d');
              $num = I('num/d');
              if($gpid > 0 && $num > 0){
                  $cart = new CartSession();
                  $res = $cart->adjust($gpid,$num);
                   if($res){
                      $data['code'] = 1;
                      $data['item_price'] = $cart->item_price($gpid);
                      $data['total_price'] = $cart->total_price();
                   }else{
                      $data['code'] = 0;
                      $data['msg'] = '购买数设置错误';
                   }
                   $this->ajaxReturn($data);
              }   
        }
   } 
   //------------------------------购物车选中商品统计
   public function cal(){ 
        if(IS_AJAX){
          $gpid_arr =  I('gpid');
          $cart = new CartSession();
          $res = $cart->cal($gpid_arr);
          if($res){
              $data['code'] = 1;
              $data['total_price'] = $res;
          }else{
              $data['code'] = 0;
              $data['msg'] = '购买数设置错误';
          }
          $this->ajaxReturn($data);
        }
   } 
   //------------------------------清空购物车
   public function clear(){
       if(IS_AJAX){
          $cart = new CartSession();
          $cart->clear();
       }
   }
   //-------------------------------购物车成功
   public function success(){ 
       $this->display();
   }
//-------------------------------------------
}

?>