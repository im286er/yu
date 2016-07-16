<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件
// 检测PHP环境 
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
// 定义应用目录
define('APP_PATH','./Application/');
//调试模式
define('APP_DEBUG',true);
//定义运行文件路径
define ('RUNTIME_PATH','./Runtime/');
//定义静态网页缓存路径
define('HTML_PATH',RUNTIME_PATH.'HtmlCache/'); 
// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';
ini_set("session.save_handler", "user");
// 亲^_^ 后面不需要任何代码了 就是如此简单