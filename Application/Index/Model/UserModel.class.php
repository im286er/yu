<?php
namespace Index\Model;
use Think\Model\RelationModel;
class UserModel extends RelationModel{
    //--------------------------------
    public function loginUpdate($userinf){    
        session('uid',$userinf['id']);
        session('user',$userinf['username']);
        /*-------------�����û���Ϣ[�Ժ�������]-----------------*/
        M()->startTrans(); //��ʼ����
            $info['last_login'] = session('last_login');
            $info['id'] = session('uid');
            $info['last_ip'] = I('server.REMOTE_ADDR');
            $info['last_login'] = time();
            //-------------���¼�������
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