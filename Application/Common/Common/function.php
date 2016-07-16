<?php
//------------------------------------------------------------------------------前后台判断登录
function isLogin(){
    if(session('uid') && session('user')){
        $ykt_token = md5(getIp().session('uid'));
        if(S('__token__'.session('uid')) == $ykt_token){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
//------------------------------------------------------------------------------前后台商品属性相关
//商品id换商品属性
function gidToField($gid,$field){
    $map['id'] = $gid;
    return M('Goods')->where($map)->getField($field); 
}
//商品私有属性id
function gpidToField($gpid,$gid='',$field){
    $map['gpid'] = $gpid;
    if($gid) $map['gid'] = $gid;
    return M('GoodsPrivate')->where($map)->getField($field);
}
//------------------------------------------------------------------------------前后台商品标签相关
//标签id换
function goodsTag($tag_id,$field){
    $map['tag_id'] = $tag_id;
    return M('GoodsTag')->where($map)->getField($field);
}

//------------------------------------------------------------------------------前后台用户相关
//根据uid获取会员项
function uidToField($uid,$field){
   if($uid){
     return M('User')->where("id=".$uid)->getField($field);
   } 
}
//根据用户名称找会员uid
function get_uid($user){
   if($user){
     $map['username'] = trim($user);
     return M('User')->where($map)->getField('id');
   }
}
//根据$uid 获取头像
function avatar($uid){
  $img = '/avatar/default.png';
  if($uid) $avatar = M('User')->where("id=".$uid)->getField('avatar');
  if($avatar) $img = $avatar;
  return $img;
}
//获取当前用户uid
function current_uid(){
   return session('uid'); 
}
//根据用户名,查看是否存在用户
function checkUser($user) {
  $map['username'] = $user;
  $uid =  M('User')->where($map)->getField('id');
  if($uid){
    return true;
  }else{
    return false;
  }
}
//根据用户id,查看是否存在用户
function checkUserId($uid) {
  $map['id'] = $uid;
  $uid =  M('User')->where($map)->getField('id');
  if($uid){
    return true;
  }else{
    return false;
  }
}
//------------------------------------------------------------------------------前后台地址相关
//商品订单页,地区id title装换
function areaName($id) {
  return M('Area')->where('id='.$id)->getField('areaname');
}
//成串地址
function addressMsg($address_id,$type){
  $res =  M('Address')->field('province,city,district,street,mobile,realname')->find($address_id);
  //$res['mobile'] = hideKey($res['mobile']);
  $res['location'] = areaName($res['province']).' '.areaName($res['city']).' '.areaName($res['district']).' '.$res['street'];
  return $res[$type];
}
//地址各项
function addressItem($id,$item){
  $res =  M('Address')->field('province,city,district,street,mobile,realname')->find($id);
  return $res[$item];
}
//------------------------------------------------------------------------------前后台通知相关
//发送通知
function priMsgSend($send_uid,$rec_uid,$title,$summary,$link,$mapping_table,$mapping_id) {
  $data['send_uid'] = $send_uid;
  $data['rec_uid'] = $rec_uid;
  $data['title'] = $title;
  $data['summary'] = $summary;
  $data['link'] = $link;
  $data['mapping_table'] = $mapping_table;
  $data['mapping_id'] = $mapping_id;
  $data['add_time'] = time();
  M('PrivateMsg')->add($data);
}
//获取通知
function priMsgReceive($mapping_table,$mapping_id,$rec_uid,$read) {
   $map['mapping_table'] = $mapping_table;
   $map['mapping_id'] = $mapping_id;
   $map['rec_uid'] = $rec_uid;
   $map['read'] = $read;
   return M('PrivateMsg')->where($map)->select();
}
//------------------------------------------------------------------------------前后台发送手机验证码
function sendSMS($mobile,$code,$tpl='5') {
    $apikey = C('SMS_API_KEY');
    $ch = curl_init();
    /* 设置验证方式 */
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));
    /* 设置返回结果为流 */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    /* 设置超时时间*/
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    /* 设置通信方式 */
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $data=array('tpl_id'=>$tpl,
                'tpl_value'=>urlencode('#code#').'='.urlencode($code).'&'.urlencode('#company#').'='.urlencode('御咖塘').'&'.urlencode('#app#').'='.urlencode('御咖塘'),
                'apikey'=>$apikey,
                'mobile'=>$mobile);
    
    curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v1/sms/tpl_send.json');
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    return curl_exec($ch);
    
    
    //--------------------------------------
    //function sendSMS($mobile,$code) {
//            header("Content-Type:text/html;charset=utf-8");
//            $apikey = C('SMS_API_KEY');$tpl = C('SMS_TPL');
//            $ch = curl_init();
//            /* 设置验证方式 */
//            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));
//            /* 设置返回结果为流 */
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            /* 设置超时时间*/
//            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
//            /* 设置通信方式 */
//            curl_setopt($ch, CURLOPT_POST, 1);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//            $data=array('tpl_id'=>$tpl,'tpl_value'=>('#code#').'='.urlencode($code),'apikey'=>$apikey,'mobile'=>$mobile);
//            curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v1/sms/tpl_send.json');
//            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
//            return curl_exec($ch);
//    }
//    
}
//---------------------------------
?>