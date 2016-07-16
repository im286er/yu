<?php
namespace Index\Controller;
use Think\Controller;
class HomeController extends CommonController {
   protected function _initialize() {
      if(!isLogin()) {
         redirect(U('Index/Login/index'));
         return false;
      }
   }
//----------------------------------------------------------------------菜单区-------------------------------------------------------------------
   //-----------------------------------------用户中心首页-------------------------
   public function index(){
      //网站公告
      $pub_msg = M('PublicMsg')->cache('Pub_Msg',60*60)->field('id,title')->order('id DESC')->limit(5)->select();
      //订单数据
      $map['uid'] = session('uid');
      $map['status'] = C('ORDER_STATUS_VAL.toPay');
      $unpay_order = D('Order')->relation('order_goods')->field('id,order_sn,order_price,add_time,status')->where($map)->select();
      //-----------------------------------------------
      $this->assign('pub_msg',$pub_msg);              //公告
      $this->assign('unpay_order',$unpay_order);      //订单情况
      $this->assign('scan_goods',cookie('scan_goods'));//最近浏览的商品
      $this->display();
   } 
   //-----------------------------------------订单-----------------------------
   //-----------我的订单
   public function order(){
      if(IS_POST){
         if(I('item'))  $map['status'] = I('item');
      } 
      $map['uid'] = session('uid');
      $this->mapSearch('Order',$map,$relation='order_goods',$field='id,order_sn,status,order_price,add_time,pay_time',$orderby='',$listRows=10);
      if(IS_POST){
         $this->display(ACTION_NAME.'_p');
      }else{
         $this->display();
      }
   }
   //-----------订单详细
   public function orderView(){
       $order_sn = I('get.orderSn/s'); $passKey = I('get.passKey/s');
       $map['uid'] = session('uid');
       $map['order_sn'] = $order_sn;
       $res = D('Order')->validate($map,$passKey);
       if($res['order_inf']){
            $order_inf = $res['order_inf'];
            if($order_inf['status'] == C('ORDER_STATUS_VAL.send')){           //已发货有物流信息
               $order_inf['express_msg'] = object_array(json_decode($order_inf['express_msg']));
            }
            $this->assign('order_inf',$order_inf);                     
            $this->display();
       }else{
            return $this->yktError($res['msg']);
       } 
   }
   //-----------商品评价
   public function orderToComment(){
      $map['uid'] = session('uid'); $map['status'] = array('between',C('ORDER_STATUS_VAL.receive').','.C('ORDER_STATUS_VAL.finish'));
      $orderId =  M('Order')->where($map)->field('id')->select();
      foreach($orderId as $k=>$v){
        $order_id_str.= $v['id'].','; 
      }
      //---------------------
      $map = array();
      $map['order_id'] = array('IN',rtrim($order_id_str,','));
      $map['uid'] = session('uid');
      $map['comment'] = 0;
      $list = M('OrderGoods')->where($map)->distinct(true)->field('gid,gpid,order_id')->order('order_id DESC')->select();
      $this->assign('list',$list);
      $this->display();
   }
   //-----------退货换货
   public function orderRefund(){
      $map['uid'] = session('uid');
      $this->mapSearch('OrderGoodsRefund',$map,$relation='false',$field='',$orderby='',$listRows='10');
      if(IS_POST){
         $this->display(ACTION_NAME.'_p');
      }else{
         $this->display();
      }
   }
   //-----------申请退货换货
   public function orderRefundApply(){
      $order_sn = I('get.orderSn/s'); $passKey = I('get.passKey/s');
      $map['uid'] = session('uid');
      $map['status'] = C('ORDER_STATUS_VAL.receive');
      $map['order_sn'] = $order_sn;
      $res = D('Order')->validate($map,$passKey);
      if($res['order_inf']){
        $this->assign('vo',$res['order_inf']);
        $this->display();
      }else{
        $this->yktError('订单信息出错');
      }
   }
   //-----------------------------------------收藏-----------------------------
   //-----------收藏商品
   public function collectGoods(){
      if(IS_POST){
         if(I('item/d'))  $map['cat_id'] = I('item/d');
      } 
      $map['uid'] = session('uid');
      $this->mapSearch('GoodsCollect',$map,$relation=false,$field='',$orderby='',$listRows=16);
      $this->assign('catlist',D('Category')->catList());
      if(IS_POST){
         $this->display(ACTION_NAME.'_p');
      }else{
         $this->display();
      }
   }
   //-----------收藏文章
   public function collectArticle(){
      $map['uid'] = session('uid');
      $this->mapSearch('ArticleCollect',$map,$relation=false,$field='',$orderby='',$listRows=16);
      if(IS_POST){
         $this->display(ACTION_NAME.'_p');
      }else{
         $this->display();
      }
   }
   //-----------------------------------------作者-----------------------------
   //-----------我的寄售
   public function consign(){
      $map['uid'] = session('uid');
      if(IS_POST){
         if(I('item'))  $map['status'] = I('item');
      } 
      $this->mapSearch('Consign',$map);
      if(IS_POST){
         $this->display(ACTION_NAME.'_p');
      }else{
         $this->display();
      }
   }
   //-----------我的投稿
   public function contribute(){
      $map['uid'] = session('uid');
      $this->mapSearch('Article',$map,$relation='cat',
                                 $field='id,title_img,title,add_time,cat_id,status',
                                 $orderby='',$listRows='10');
      if(IS_POST){
         $this->display(ACTION_NAME.'_p');
      }else{
         $this->display();
      }
   }
   //-----------------------------------------信息中心-----------------------------
   //-----------用户通知
   public function priMsg(){
      $map['rec_uid'] = session('uid');
      $this->mapSearch('PrivateMsg',$map);
      if(IS_POST){
         $this->display(ACTION_NAME.'_p');
      }else{
         $this->display();
      }
   }
   //-----------------------------------------账号-----------------------------
   //账户安全
   public function account(){
      $map['id'] = session('uid');
      $this->assign('inf',M('User')->where($map)->find());
      $this->display();
   }
   //个人资料
   public function profile(){
      $map['id'] = session('uid');
      $this->assign('inf',M('User')->where($map)->find());
      $this->display();
   }
   //实名认证
   public function auth(){
      $map['id'] = session('uid');
      $this->assign('inf',M('User')->where($map)->find());
      $this->display();
   }
   //头像设置
   public function avatar() {
      $this->display();
   }
   //收货地址管理
   public function address(){
      $map['uid'] = session('uid');
      $this->assign('list',M('Address')->where($map)->limit(5)->select());
      $this->assign('num',M('Address')->where($map)->count());
      $this->display();
   }
/*
 -------------------------------------------操作区------------------------------------------  
 */
   //订单-----------------申请退换货-----------------------------
   public function doOrderRefund(){
       $model = D('OrderGoodsRefund');
       if (!$model->create()){  
              $this->yktError($model->getError());
       }else{
           $map['uid'] = session('uid');$map['order_id'] = I('order_id');$map['gpid'] = I('gpid');$map['gid'] = I('gid');
           $res = $model->where($map)->find(); 
           if($res['id']) $this->yktSuccess('退/换货申请已提交,请留意退货换货页面',U('Index/Home/orderRefund'));
           M()->startTrans(); //开始事务
           $gpid = M('OrderGoods')->where($map)->getField('gpid');          
           $order_inf = M('Order')->field('uid,status')->find(I('order_id'));
           if($gpid > 0 && $order_inf['uid']==session('uid') && $order_inf['status'] == C('ORDER_STATUS_VAL.receive')){
              if($model->add()){
                M()->commit();
                $this->yktSuccess('退换货申请提交成功!',U('Index/Home/orderRefund'));
              }else{
                M()->rollback();
                $this->yktError('退换货申请提交失败!');return false;
              }
           }else{
              M()->rollback();
              $this->yktError('订单信息出错');return false;
           }
       }
   }
   //消息-----------------用户通知-----------------------------
   //设为已读
   public function priMsgRead(){
      $map['rec_uid'] = session('uid');
      $map['id'] = array('IN',I('ids'));
      $data['read'] = 1;
      M('PrivateMsg')->where($map)->save($data);
   }
//-------------------------------------------------------------------------
}

?>