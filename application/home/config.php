<?php
//配置文件
return [

    'template'=>[
        'layout_on'=>true,//开启全局布局
        'layout_name'=>'layout'//布局文件名字
    ],
    'captcha'  => [
            // 验证码字符集合
        'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
        // 验证码字体大小(px)
        'fontSize' => 25,
        // 是否画混淆曲线
        'useCurve' => true,
         // 验证码图片高度
        'imageH'   => 15,
        // 验证码图片宽度
        'imageW'   => 100,
        // 验证码位数
        'length'   => 5,
        // 验证成功后是否重置
        'reset'    => true
    ],
    //redis配置
//    'cache'  => [
//        'type'      => 'redis',
//        'host'      => '123.57.95.65',
//        'port'      => '6379',//你redis的端口号，可以在配置文件设置其他的
//        'password'  => 'aaaa8595586', //这里是你redis配置的密码，如果没有则留空
//    ],
];