<?php
namespace Index\Util;
class WeChat{

    const MSGTYPE_TEXT = 'text';
    const MSGTYPE_IMAGE = 'image';
    const MSGTYPE_LOCATION = 'location';
    const MSGTYPE_LINK = 'link';
    const MSGTYPE_EVENT = 'event';
    const MSGTYPE_MUSIC = 'music';
    const MSGTYPE_NEWS = 'news';
    const MSGTYPE_VOICE = 'voice';
    const MSGTYPE_VIDEO = 'video';
    const MSGTYPE_THUMB	= 'thumb';

    private $token;
    private $appid;
    private $appsecret;
    private $access_token;
    private $_msg;
    private $_funcflag = false;
    private $_receive;
    public $debug =  false;
    private $_logcallback;

    public function setOptions($options){
        $this->token = isset($options['token'])?$options['token']:'';
        $this->appid = isset($options['appid'])?$options['appid']:'';
        $this->appsecret = isset($options['appsecret'])?$options['appsecret']:'';
        $this->debug = isset($options['debug'])?$options['debug']:false;
        $this->_logcallback = isset($options['logcallback'])?$options['logcallback']:false;
    }

    /**
     * For weixin server validation
     */
    private function checkSignature()
    {
        $signature = isset($_GET["signature"])?$_GET["signature"]:'';
        $timestamp = isset($_GET["timestamp"])?$_GET["timestamp"]:'';
        $nonce = isset($_GET["nonce"])?$_GET["nonce"]:'';

        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr,SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * For weixin server validation
     * @param bool $return 是否返回
     */
    public function valid($return=false)
    {
        $echoStr = isset($_GET["echostr"]) ? $_GET["echostr"]: '';
        if ($return) {
            if ($echoStr) {
                if ($this->checkSignature())
                    return $echoStr;
                else
                    return false;
            } else
                return $this->checkSignature();
        } else {
            if ($echoStr) {
                if ($this->checkSignature())
                    die($echoStr);
                else
                    die('no access');
            }  else {
                if ($this->checkSignature())
                    return true;
                else
                    die('no access');
            }
        }
        return false;
    }

    /**
     * 设置发送消息
     * @param array $msg 消息数组
     * @param bool $append 是否在原消息数组追加
     */
    public function Message($msg = '',$append = false){
        if (is_null($msg)) {
            $this->_msg =array();
        }elseif (is_array($msg)) {
            if ($append)
                $this->_msg = array_merge($this->_msg,$msg);
            else
                $this->_msg = $msg;
            return $this->_msg;
        } else {
            return $this->_msg;
        }
    }

    public function setFuncFlag($flag) {
        $this->_funcflag = $flag;
        return $this;
    }

    private function log($log){
        if ($this->debug && function_exists($this->_logcallback)) {
            if (is_array($log)) $log = print_r($log,true);
            return call_user_func($this->_logcallback,$log);
        }
    }

    /**
     * 获取微信服务器发来的信息
     */
    public function getRev(){
        $postStr = file_get_contents("php://input");
        if (!empty($postStr)) {
            $this->_receive = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        return $this;
    }
    
    /**
     * 获取微信服务器发来的信息数组
     */
    public function getRevMsg(){
    	return $this->_receive ? $this->_receive : array();
    }
    
    /**
     * 获取消息发送者
     */
    public function getRevFrom() {
        if ($this->_receive)
            return $this->_receive['FromUserName'];
        else
            return false;
    }

    /**
     * 获取消息接受者
     */
    public function getRevTo() {
        if ($this->_receive)
            return $this->_receive['ToUserName'];
        else
            return false;
    }

    /**
     * 获取接收消息的类型
     */
    public function getRevType() {
        if (isset($this->_receive['MsgType']))
            return $this->_receive['MsgType'];
        else
            return false;
    }

    /**
     * 获取消息ID
     */
    public function getRevID() {
        if (isset($this->_receive['MsgId']))
            return $this->_receive['MsgId'];
        else
            return false;
    }

    /**
     * 获取消息发送时间
     */
    public function getRevCtime() {
        if (isset($this->_receive['CreateTime']))
            return $this->_receive['CreateTime'];
        else
            return false;
    }

    /**
     * 获取接收消息内容正文
     */
    public function getRevContent(){
        if (isset($this->_receive['Content']))
            return $this->_receive['Content'];
        else
            return false;
    }

    /**
     * 获取接收消息图片
     */
    public function getRevPic(){
        if (isset($this->_receive['PicUrl']))
            return $this->_receive['PicUrl'];
        else
            return false;
    }

    /**
     * 获取接收消息链接
     */
    public function getRevLink(){
        if (isset($this->_receive['Url'])){
            return array(
                'url'=>$this->_receive['Url'],
                'title'=>$this->_receive['Title'],
                'description'=>$this->_receive['Description']
            );
        } else
            return false;
    }

    /**
     * 获取接收地理位置
     */
    public function getRevGeo(){
        if (isset($this->_receive['Location_X'])){
            return array(
                'x'=>$this->_receive['Location_X'],
                'y'=>$this->_receive['Location_Y'],
                'scale'=>$this->_receive['Scale'],
                'label'=>$this->_receive['Label']
            );
        } else
            return false;
    }

    /**
     * 获取接收事件推送
     */
    public function getRevEvent(){
        if (isset($this->_receive['Event'])){
            $ret = array(
                'event'=>$this->_receive['Event'],
                'key'=>$this->_receive['EventKey'],
            );
            if(isset($this->_receive['Ticket'])){
            	$ret['ticket'] = $this->_receive['Ticket'];
            }
            if(isset($this->_receive['EventKey'])){
            	$ret['eventKey'] = $this->_receive['EventKey'];
            }
            return $ret;
        } else
            return false;
    }
    
    /**
     * 获取接收语言推送
     */
    public function getRevVoice(){
        if (isset($this->_receive['MediaId'])){
            $ret = array(
                'mediaid'=>$this->_receive['MediaId'],
                'format'=>$this->_receive['Format'],
            );
            if(isset($this->_receive['Recognition'])){
            	$ret['recognition'] = $this->_receive['Recognition'];
            }
            return $ret;
        } else
            return false;
    }

    public static function xmlSafeStr($str)
    {
        return '<![CDATA['.preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/",'',$str).']]>';
    }

    /**
     * 数据XML编码
     * @param mixed $data 数据
     * @return string
     */
    public static function data_to_xml($data) {
        $xml = '';
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = "item id=\"$key\"";
            $xml    .=  "<$key>";
            $xml    .=  ( is_array($val) || is_object($val)) ? self::data_to_xml($val)  : self::xmlSafeStr($val);
            list($key, ) = explode(' ', $key);
            $xml    .=  "</$key>";
        }
        return $xml;
    }

    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string $item 数字索引的子节点名
     * @param string $attr 根节点属性
     * @param string $id   数字索引子节点key转换的属性名
     * @param string $encoding 数据编码
     * @return string
     */
    public function xml_encode($data, $root='xml', $item='item', $attr='', $id='id', $encoding='utf-8') {
        if(is_array($attr)){
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr   = trim($attr);
        $attr   = empty($attr) ? '' : " {$attr}";
        $xml   = "<{$root}{$attr}>";
        $xml   .= self::data_to_xml($data, $item, $id);
        $xml   .= "</{$root}>";
        return $xml;
    }

    /**
     * 设置回复消息
     * Examle: $obj->text('hello')->reply();
     * @param string $text
     */
    public function text($text='')
    {
        $FuncFlag = $this->_funcflag ? 1 : 0;
        $msg = array(
            'ToUserName' => $this->getRevFrom(),
            'FromUserName'=>$this->getRevTo(),
            'MsgType'=>self::MSGTYPE_TEXT,
            'Content'=>$text,
            'CreateTime'=>time(),
            'FuncFlag'=>$FuncFlag
        );
        $this->Message($msg);
        return $this;
    }
    
    /**
     * 设置回复图片
     */
    public function image($media_id='')
    {
    	$msg = array(
    			'ToUserName' => $this->getRevFrom(),
    			'FromUserName'=>$this->getRevTo(),
    			'MsgType'=>self::MSGTYPE_IMAGE,
    			'CreateTime'=>time(),
    			'Image'	=> array(
    					'MediaId'=>$media_id
    			)
    	);
    	$this->Message($msg);
    	return $this;
    }
    

    /**
     * 设置回复音乐
     * @param string $title
     * @param string $desc
     * @param string $musicurl
     * @param string $hgmusicurl
     */
    public function music($title,$desc,$musicurl,$hgmusicurl='') {
        $FuncFlag = $this->_funcflag ? 1 : 0;
        $msg = array(
            'ToUserName' => $this->getRevFrom(),
            'FromUserName'=>$this->getRevTo(),
            'CreateTime'=>time(),
            'MsgType'=>self::MSGTYPE_MUSIC,
            'Music'=>array(
                'Title'=>$title,
                'Description'=>$desc,
                'MusicUrl'=>$musicurl,
                'HQMusicUrl'=>$hgmusicurl
            ),
            'FuncFlag'=>$FuncFlag
        );
        $this->Message($msg);
        return $this;
    }

    /**
     * 设置回复图文
     * @param array $newsData
     * 数组结构:
     *  array(
     *  	[0]=>array(
     *  		'Title'=>'msg title',
     *  		'Description'=>'summary text',
     *  		'PicUrl'=>'http://www.domain.com/1.jpg',
     *  		'Url'=>'http://www.domain.com/1.html'
     *  	),
     *  	[1]=>....
     *  )
     */
    public function news($newsData=array())
    {
        $FuncFlag = $this->_funcflag ? 1 : 0;
        $count = count($newsData);

        $msg = array(
            'ToUserName' => $this->getRevFrom(),
            'FromUserName'=>$this->getRevTo(),
            'MsgType'=>self::MSGTYPE_NEWS,
            'CreateTime'=>time(),
            'ArticleCount'=>$count,
            'Articles'=>$newsData,
            'FuncFlag'=>$FuncFlag
        );
        $this->Message($msg);
        return $this;
    }

    /**
     *
     * 回复微信服务器, 此函数支持链式操作
     * @example $this->text('msg tips')->reply();
     * @param string $msg 要发送的信息, 默认取$this->_msg
     * @param bool $return 是否返回信息而不抛出到浏览器 默认:否
     */
    public function reply($msg=array(),$return = false)
    {
        if (empty($msg))
            $msg = $this->_msg;
        $xmldata=  $this->xml_encode($msg);
        $this->log($xmldata);
        if ($return)
            return $xmldata;
        else
            echo $xmldata;
    }
    
    /**
     * 获取使用凭证access_token,为对公众账号的自定义菜单进行创建、查询和删除等操作所需要
     * @param bool $return 是否返回令牌:否
     * @return mixed $ret 视参数$return而定：是否成功获得令牌
     */
    public function getAccessToken(){
         $app_id = $this->appid;
         $appsecret = $this->appsecret;
         $token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$app_id.'&secret='.$appsecret;
         $ch = curl_init();
    	 curl_setopt($ch, CURLOPT_URL, $token_url);
    	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	 $json = curl_exec($ch);
    	 curl_close($ch);
    	 $token_msg = json_decode($json,true);
         $access_token =  $token_msg['access_token'];
         return $access_token;
    }
    
    
	/**
     * 拉取用户基本信息
     * @param string $openid
     * @return array $userinfo
     */
    public function pullUserInfo($openid){
    	$req_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s";
    	$req_url = sprintf($req_url, $this->access_token, $openid);
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $req_url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$metadata = curl_exec($ch);
    	curl_close($ch);
    	$userinfo = json_decode($metadata,true);
    	return $userinfo['openid'] ? $userinfo : array();
    }

    
    /**
     * 获取关注者列表
     * @param string $next_openid 第一个拉取的OPENID，不填默认从头开始拉取
     * @return array $userlist
     * @example http://mp.weixin.qq.com/wiki/index.php?title=获取关注者列表
     */
    public function pullUserList($next_openid=''){
    	$req_url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=%s&next_openid=%s";
    	$req_url = sprintf($req_url, $this->access_token, $next_openid);
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $req_url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$json = curl_exec($ch);
    	curl_close($ch);
    	$userlist = json_decode($json,true);
    	return $userlist['count']>0 ? $userlist : array();
    }
    
    
    /**
     * 上传多媒体文件
     * @param string $type 多媒体类型
     * @param string $media 多媒体文件
     * @return json
     */
    public function uploadMedia($type, $media){
    	$this->getAccessToken();
    	$req_url = 'http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=%s&type=%s';
    	$req_url = sprintf($req_url, $this->access_token, $type);
    	$post = array('media'=>"@{$media}");
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $req_url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_POST, true);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    	$ret_msg = curl_exec($ch);
    	curl_close($ch);
    	return json_decode($ret_msg, true);
    }
    
    
    /**
     * 下载多媒体文件
     * @param string $media_id 多媒体文件标识
     * @param string $format 多媒体文件格式
     * @param string $path 多媒体文件存放目录
     * @return array 微信接口返回json
     */
    public function downloadMedia( $media_id, $format, $path='voice', $citme ){
    	$path_list = array('voice');
    	$path = (in_array($path, $path_list) ? $path : 'voice').'/';
    	$subpath = date('Y/m/',$citme?$citme:time());
    	$real_path = C('ND_TITLE_IMG_PATH').'weixin/media/'.$path.$subpath;
    	mk_dir($real_path);
    	
    	$file_name = substr($media_id, 0, 10);
    	$media_file = $real_path.$file_name.'.'.$format;
    	
    	$req_url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=%s&media_id=%s';
    	$req_url = sprintf($req_url, $this->access_token, $media_id);
    	
    	$fp = fopen($media_file, 'w+');
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $req_url);
    	curl_setopt($ch, CURLOPT_FILE, $fp);
    	$ret_msg = curl_exec($ch);
    	curl_close($ch);
    	fclose($fp);
    	@chmod($media_file, 0775);
    	return json_decode($ret_msg, true);
    }
    
    
    /**
     * 发送客服消息
     * @param json $json 回复消息体
     * @example 消息json格构参见   http://mp.weixin.qq.com/wiki/index.php?title=发送客服消息
     * @return json $ret_json
     */
    public function customSend($json){
    	$this->getAccessToken();
    	$req_url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s';
    	$req_url = sprintf($req_url, $this->access_token);
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $req_url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_POST, true);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    	$ret_json = curl_exec($ch);
    	curl_close($ch);
    	return json_decode($ret_json, true);
    }
    
    /**
     * 发送客服消息:文本
     * @param string $openid
     * @param string $text 文本消息内容
     */
    public function customSendText($openid, $text){
    	$msg_data = array(
    			'touser'	=>	$openid,
    			'msgtype'	=>	'text',
    			'text'		=>	array('content'=>$text)
    	);
    	$json = $this->json_keep_chinese($msg_data, array('content'));
    	$res = $this->customSend($json);
    	return $res['errmsg']=='ok' ? true : false;
    }
    
    
 	/**
     * 发送客服消息:图文
     * @param string $openid
     * @param string $text 文本消息内容
     */
    public function customSendImage($openid, $mediaId){
    	$msg_data = array(
    			'touser'	=>	$openid,
    			'msgtype'	=>	'image',
    			'image'		=>	array('media_id'=>$mediaId)
    	);
    	$json = json_encode($msg_data);
    	$res = $this->customSend($json);
    	return $res['errmsg']=='ok' ? true : false;
    }
    
    
    /**
     * 生成JSON,指定内容不做转码
     * @param array $arr 输入数据
     * @param array $need_key 需要保留原字符的键名
     * @return json
     */
    protected function json_keep_chinese($arr, $need_key){
    	$vars = array();
    	$flag = 0;
    	foreach ($arr as $key=>&$item){
    		if(is_array($item)){
    			foreach ($item as $skey=>&$sitem){
    				if(in_array($skey, $need_key)){
    					$vars[0][] = $sitem;
    					$sitem = $vars[1][] = '@@'.$flag.'##';
    					$flag++;
    				}
    			}
    		}else{
    			if(in_array($key, $need_key)){
    				$vars[0][] = $item;
    				$item = $vars[1][] = '@@'.$flag.'##';
    				$flag++;
    			}
    		}
    	}
    	$json = json_encode($arr);
    	$ret = str_replace($vars[1], $vars[0], $json);
    	return $ret;
    }
   
    
//------------------------------------------    
}
?>