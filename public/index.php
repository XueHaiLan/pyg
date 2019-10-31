<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
if($_SERVER['REQUEST_METHOD']=='OPTTIONS'){
    header("Access-Control-Allow-Origin: http://localhost:8080");   //全域名
    header("Access-Control-Allow-Credentials: true");   //是否可以携带cookie

    header("Access-Control-Allow-Methods: POST,GET,PUT,OPTIONS,DELETE");   //允许请求方式
    header("Access-Control-Allow-Headers: X-Custom-Header");   //允许请求字段，由客户端决定
}

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
