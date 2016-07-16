<?php
return array(
	//'配置项'=>'配置值'
    'DEFAULT_THEME' => 'ykt',  
    //静态缓存
    'HTML_CACHE_ON'     =>    true, // 开启静态缓存
    'HTML_CACHE_TIME'   =>    60,   // 全局静态缓存有效期（秒）
    'HTML_FILE_SUFFIX'  =>    '.shtml', // 设置静态缓存文件后缀
    'HTML_CACHE_RULES'  =>     array(  // 定义静态缓存规则
       'Cart:success'=>array('Cart/success',60*5),
       'Article:detail'=>array('Article/detail_{id}',60*5),
       //'Consign:apply'=>array('Consign/apply_{cat}',60*50),
    ),
    //表单令牌
    'TOKEN_ON'      =>    true,  // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'    =>    '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true
    //头像基本地址
    'AVATAR_PATH'      => './Uploads/avatar'.DIRECTORY_SEPARATOR,
    //头像上传地址
    'AVATAR_DATE_PATH' => './Uploads/avatar'.DIRECTORY_SEPARATOR.date('Y-m').DIRECTORY_SEPARATOR,
    //前台分页变量
    'VAR_PAGE'=>'p',
    //前台分页条数
    'PAGE_LISTROWS' => 10,                     
);
?>