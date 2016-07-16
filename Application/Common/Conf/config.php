<?php
/*-----------------------------------------全局配置-----------------------------*/ 
/*----------------全局基础配置---------------------*/ 
return array(
     //数据库
	'DB_TYPE' => 'mysql',
	'DB_HOST' => '127.0.0.1',
	'DB_NAME' => 'yukatang',
	'DB_PWD' => '0c126158c3',
	'DB_USER' => 'yukatangqwer',
	'DB_PORT' => '3306',
	'DB_PREFIX' => 'ykt_',
    //页面trace
    'SHOW_PAGE_TRACE' => false, 
    //分页配置
    'PAGE_LISTROWS' => 15,                      //每页显示的记录数，初始显示
    //URL模式配置
    'URL_CASE_INSENSITIVE' => true, //URL不区分大小写
	'URL_HTML_SUFFIX' => 'html',    //设置伪静态后缀名
	'DEFAULT_MODULE' => 'Index',    // 默认模块
    'URL_MODEL'      =>  2,         //URL模式
    //模板替换设置
    'TMPL_PARSE_STRING' =>array(
		'__UPLOAD__'=>'/Uploads',   //上传路径替换规则
        '__SITE__'=>'http://119.29.89.188',
	),
    //全局post,get数据过滤
    'DEFAULT_FILTER'=>'htmlspecialchars,mysql_escape_string,strip_tags,trim',
    //redis缓存配置
    //'DATA_CACHE_PREFIX' => 'ykt_',
    'DATA_CACHE_TYPE'                   => 'Redis',
    'REDIS_HOST'                        => '127.0.0.1',
    'REDIS_PORT'                        => '6379',
    'DATA_CACHE_TIME'                   => 3600*3,
    'REDIS_CTYPE'           => 2, //连接类型 1:普通连接 2:长连接  
    'REDIS_TIMEOUT'         => 0, //连接超时时间(S) 0:永不超时
    //redis session存放配置
    'SESSION_TYPE' => 'Redis', //session保存类型
    'SESSION_PREFIX' => 'sess_', //session前缀
    'SESSION_EXPIRE' => 3600*3, //SESSION过期时间 
    //cookie设置
    'COOKIE_SECURE'         =>  false,   // Cookie安全传输 就系是否通过ssl
    'COOKIE_HTTPONLY'       =>  true,    // Cookie httponly设置
    'COOKIE_EXPIRE'         =>  86400,   //Cookie 生效时间
    'MODULE_DENY_LIST'      =>  array('Common'), // 禁止访问的模块列表
/*----------------全局业务配置---------------------*/
    //网站地址
    'WEB_SITE'=>'http://www.yukatang.com',
    //图片服务器
    'IMG_SITE'=>'http://img.yukatang.com',
    //支付宝应用配置  
	'Alipay'   =>array(
        'seller_email'=>'yukatang@aliyun.com',                       //这里是卖家的支付宝账号
        'partner' =>'2088812102724568',                              //这里是你在成功申请支付宝接口后获取到的PID；
	    'key'=>'pmzo22kcrmgdzdljr224rp9ytrqjnktm',                   //这里是你在成功申请支付宝接口后获取到的Key
	    'cacert'=> getcwd().'/Application/Common/Conf/cacert.pem',   //证书地址
        'transport' => 'http',
        'sign_type' => strtoupper('MD5'),
        'input_charset' => strtolower('utf-8'),
        //-------------------------------------------------------------
		'notify_url'=>'http://119.29.89.188/Index/Pay/notifyUrl',  //这里是异步通知页面url
		'return_url'=>'http://119.29.89.188/Index/Pay/returnUrl',  //这里是页面跳转通知url
		'error_notify_url'=>'http://119.29.89.188/Index/Pay/errorNotifyUrl',
	),
    //短信发送配置参数
    'SMS_API_KEY'=>'59f1bfb06bd66559af0ddc4f67b187a5',
    'EXPRESS_API_KEY'=>'c1188c74a476321f02789e971eccfd48',
    //上传设置
    'UPLOAD_BASE_CONFIG' =>array(
                                 'maxSize' => 2*1024*1024,                   //上传最大2M
                                 'saveName' => array('date','YmdHis'),       //上传文件命名
                                 'exts' => array('jpg','gif','png','jpeg'),  //上传文件类型
                                 'autoSub' => true,
                                 'subName' => array('date','Y-m-d'),         //上传子目录按年月日命名
                                 ),
 /*----------------全局加载配置---------------------*/
   'LOAD_EXT_CONFIG' => 'base',            //加载业务基础数据配置
   'LOAD_EXT_FILE' => 'tool,salt_hash',    //加载全局特定功能函数,包括工具函数和加盐hash函数
//------------------------------------------------------------------------------------------------
);
?>
