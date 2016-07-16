<?php
namespace Index\Util;
class CartSession{
     /**
     * 设置购物车数组原型
     *     $cart['2']['num'] = 5,2为gpid  num为数量
     */
	//----------------------------------------------------------私有方法----------------------------
    //设置购物车数组
    private function _setCart($cart){
        if(is_array($cart) && !empty($cart)){
            session('_yktsc_',$cart);
        }else{
            session('_yktsc_',null);
        }
    }
    //获取购物车数组
    private function _getCart(){
        $cart = session('_yktsc_');
        if(is_array($cart) && !empty($cart)){
            return session('_yktsc_');
        }
    }
    
    //----------------------------------------------------------购物车操作-------------------------
    //添加购物车
    public function add($gpid,$num,$price){
             $cart = array();
             $cart = $this->_getCart();                      //当前购物车数组
             if(array_key_exists($gpid,$cart)){              //购物车已存在该商品
                  $cart[$gpid]['num'] += $num;
             }else{                                          //购物车不存在该商品
                  $cart[$gpid]['num'] = $num;
                  $cart[$gpid]['price'] = $price;
             }
             $this->_setCart($cart);
             return true;  
    }
    
    //数量增加1
    public function increase($gpid){
             $cart = array();
             $cart = $this->_getCart();                      
             if(array_key_exists($gpid,$cart)){             
                  $cart[$gpid]['num']++ ;
             }
             $this->_setCart($cart);
             return true;  
    }
    //数量减少1
    public function reduce($gpid){
        $cart = $this->_getCart();
        if(array_key_exists($gpid,$cart)){                   
           if($cart[$gpid]['num'] >= 2){                     
             $cart[$gpid]['num']--;
             $this->_setCart($cart);
             return true; 
           }
        }
    }
    //移除商品
    public function remove($gpid){
        $cart = $this->_getCart();                        
        if(array_key_exists($gpid,$cart)){                   
           unset($cart[$gpid]);
           $this->_setCart($cart);
           return true;
        }
    }
    //调整数量
    public function adjust($gpid,$num){
        $cart = $this->_getCart();                       
        if(array_key_exists($gpid,$cart)){                
          $cart[$gpid]['num'] = $num;
          $this->_setCart($cart);
          return true;
        }
    }
    //----------------------------------------------------------购物车数据-------------------------
    //购物车查询数组
    public function read(){   
        $res = array();
        $cart = $this->_getCart();         
        if(!empty($cart)){
              $res = D('GoodsPrivate')->cgInfo($cart);
        }else{
            return NULL;
        }
        return $res;
    }
    //购物车单项小计
    public function item_price($gpid){   
        $cart = $this->_getCart();         
        if(array_key_exists($gpid,$cart)){                
          return $cart[$gpid]['price'] * $cart[$gpid]['num'];
        }
    }
    //购物车商品总价
    public function total_price(){  
       $cart = $this->_getCart(); 
       $total = 0;
       foreach($cart as $k=>$v){
          $total+=$v['num']*$v['price'];
       }
       return $total;
    }
    //购物车商品件数
    public function amount(){ 
       $amount = 0;
       $cart = $this->_getCart(); 
       foreach($cart as $k=>$v){
        $amount+=$v['num'];
       }
       return $amount;
    }
    //购物车选中商品统计价格
    public function cal($gpid_arr){  
       $res = 0;
       $cart = $this->_getCart(); 
       foreach($gpid_arr as $k=>$v){
          if(array_key_exists($v,$cart)){                
            $res += $cart[$v]['num'] * $cart[$v]['price'];
          }
       }
       return $res;
    }
   //---------------------------------------------------------清除-------------------------
   public function clear(){
        session('_yktsc_',NULL);
   }
//-------------------------
}
?>