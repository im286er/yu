<?php
namespace Index\Controller;
use Think\Controller;
use Think\Cache;
class TestController extends CommonController {
   //-------------------------------------------
   public function testInfo(){
    
    // $link[1] = array('text'=>'通贩区','link'=>'/Index/Sale/index');
    //  $link[2] = array('text'=>'回到个人中心','link'=>'/Index/User/index');
     $this->yktSuccess('投稿成功!','/Index/Sale/index',2,$link);
     $this->yktError('失败!','/Index/Sale/index',2);
   }
   //-------------------------------------------
   public function flush(){
     $redis = Cache::getInstance();
     pr($redis->flushAll());
   }
   //-------------------------------------------
   public function keys(){
      $redis = Cache::getInstance();
      pr($redis->keys('*'));
   }
   //-------------------------------------------
   public function import_csv(){
        $p = I('get.p/d');
        if($p=='') return false;
        $goods = F('import_goods_'.$p);
        foreach($goods as $k=>$v){
            
            $img = getHTTPS($v['goods_img']);
            $import_path = '/goods/'.date('Y-m-d').'/import_'.$p.'_'.$k.'.'.$v['goods_img_ext'];
            $real_import_path = './Uploads'.$import_path;
            file_put_contents($real_import_path,$img);
             
            $data['goods_name'] = $v['goods_name'];
            $data['goods_detail'] = $v['goods_detail'];
            $data['goods_img'] = $import_path;
            $data['cat_id'] = $v['cat_id'];
            $data['on_sale'] = $v['on_sale'];
            $data['original'] = $v['original'];
            $data['author'] = $v['author'];
            $data['goods_ps'] = $v['goods_ps'];
            $data['add_time'] = time();
            $data['onsale_time'] = time();
            $data['express_charge'] = 1;
            $data['status'] = 2;
            $gid = M('Goods')->add($data); 
             
            $gp['gid'] = $gid;
            $gp['stock'] = $v['stock'];
            $gp['default'] = 1;
            $gp['price'] = $v['price'];
            $gp['weight'] = $v['weight'];
            $gp['goods_code'] = '1-'.$gid.'-001';
            $gp['abbr'] = $v['abbr'];
            $gpid = M('GoodsPrivate')->add($gp);
        }
   }
   //--------------------------------------------
   public function salt(){
     pr(create_hash('wasdws46'));
     var_dump(validate_password('wasdws46','tujNR6WTFcpaVpVz8+FANAbOMT+b4WSQ','LdjmsqgjGIk3Eh1j+mNgbNkyAcG9iyqC'));
   }
   //--------------------------------------------
   public function export_excel(){
       $arr = M('User')->field('id,username,salt,hash')->select();
       exportexcel($arr,array('id','用户','秘钥','姓名'),'user');
   }
   //--------------------------------------------
   public function kuaidi(){
      $url = 'http://api.kuaidi.com/openapi.html?id=c1188c74a476321f02789e971eccfd48&com=jd&nu=15308913140&show=0&muti=0&order=desc';
      $map['order_sn'] = '1604145355509879';
      M('Order')->where($map)->save(array('express_msg'=>file_get_contents($url)));
   }
   //----------------------------------------------
}

?>