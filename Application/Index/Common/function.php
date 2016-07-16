<?php
/*---------------------------------------------- 用户未读私信-------------------------------*/
//用户未读私信
function priMsgUnread(){
    $map['rec_uid'] = session('uid');
    $map['read'] = 0;
    return M('PrivateMsg')->where($map)->count(); 
}
/*---------------------------------------------- 文章字段-----------------------------------*/
//文章id换文章属性
function aidToField($aid,$field){
    $map['id'] = $aid;
    return M('Article')->where($map)->getField($field); 
}
/*-----------------------------------------------前台注册-----------------------------------*/
//判断密码格式
function checkPassword($pwd) {
   $preg = '/[A-Za-z]*/';
   if(preg_match($preg,$pwd) && strlen($pwd) >= 6) {
      return true;
   } else {
      return false;
   }
}
/*----------------------------------------前台商品分类列表-----------------------------------*/
//生成属性选择连接
function attrUrl($key,$num,$attr,$sub_id){
     $attr_format['2'] = '0,0';
     $attr_format['3'] = '0,0,0';
     $attr_format['4'] = '0,0,0,0';
     $attr_format['5'] = '0,0,0,0,0';
     $attr_format['6'] = '0,0,0,0,0,0';
     if($attr=='') $attr = $attr_format[$num];
     $attr_arr = explode(',',$attr);
     if($sub_id==0){  //全部选项
        $attr_arr[$key-1] = 0;
     }else{
        $attr_arr[$key-1] = $sub_id;
     }
     $url = implode(',',$attr_arr);
     return $url;
}
//判断属性选择状态
function attrSelect($key,$attr,$sub_id){
      $attr_arr = explode(',',$attr);
      if($sub_id==0){  //全部选项
        if($attr_arr[$key-1]==0){
           return 'active';
        }
      }else{
        if($attr_arr[$key-1]==$sub_id){
           return 'active';
        }
      }
}
/*----------------------------------------前台寄售页----------------------------------*/
//商品属性命中(寄售详细,修改)
function attrHit($id,$ids){
  $arr = explode(',',$ids);
  if(in_array($id,$arr)){
    return 'active';
  }else{
    return false;
  }
}
//商品属性命中(寄售详细,修改)
function attrChecked($id,$ids){
  $arr = explode(',',$ids);
  if(in_array($id,$arr)){
    return 'checked';
  }else{
    return false;
  }
}
/*----------------------------------------前台订单函数-----------------------------------*/
//订单号加密
function cryptKey($order_sn){
   $key =  md5(crypt($order_sn,substr($order_sn,0,2)));
   return strtoupper($key);
}
//-------------------------前台商品相关
//商品是否可销售状态
function goodsSaleAllow($gid){
    $map['id'] = $gid ;
    $res = M('Goods')->where($map)->field('on_sale,pre_sale')->find();
    if($res['on_sale']==1 && $res['pre_sale']==0){          //上架而且不是预告
        return true;
    }else{
        return false; 
    }  
}
//-------------------------前台用户默认送货地址
function addressChoosed(){
    $map['uid'] = session('uid');
    $map['default'] = 1;
    $default_address_id =  M('Address')->where($map)->getField('id');
    if($default_address_id){
        return $default_address_id;
    }else{
        return NULL;
    }
}
//-------------------------前台计算运费
function calExpressFee($address_id,$weight){
   $cost = 0;
   if($weight > 0 && $address_id > 0){                                                //重量大于0才计算
       $city =  M('Address')->where('id='.$address_id)->getField('city');             //市
       $province =  M('Address')->where('id='.$address_id)->getField('province');     //省
       $ex = D('Expressage');
       if($ex->areaExist($city)){                    //假如运费存在市标准                                 
          $cost =   floatval($ex->cal($weight,$city));
       }else if($ex->areaExist($province)){          //假如运费存在省标准
          $cost =   floatval($ex->cal($weight,$province));
       }else{
          $cost =   floatval($ex->cal($weight,0));   //默认运费
       } 
   }
   return $cost;    
}
/*--------------------------------------------商品导入--------------------------------------------*/
function getHTTPS($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_REFERER, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
}
/*--------------------------------------------------------------------------------------------*/
?>