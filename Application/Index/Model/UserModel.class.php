<?php
namespace Index\Model;
use Think\Model\RelationModel;
class UserModel extends RelationModel{
    //--------------------------------
    public function loginUpdate($userinf){    
        session('uid',$userinf['id']);
        session('user',$userinf['username']);
        /*-------------更新用户信息[以后做队列]-----------------*/
        M()->startTrans(); //开始事务
            $info['last_login'] = session('last_login');
            $info['id'] = session('uid');
            $info['last_ip'] = I('server.REMOTE_ADDR');
            $info['last_login'] = time();
            //-------------更新寄售数量
            if($userinf['consigner'] == C('CONSIGN_QUALI.qualified')){
                $map = array();
                $map['c_uid'] = session('uid');$map['on_sale'] = 1;
                $info['consign_num'] = M('Goods')->where($map)->count();     
            }
            $this->where('id='.session('uid'))->save($info);
            $ykt_token = md5(getIp().session('uid'));
            S('__token__'.session('uid'),$ykt_token);
        M()->commit();       
    }   
//------------------------------------------
}
?>