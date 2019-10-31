<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//
//];

use think\Route;

Route::domain('api',function(){
    Route::get('/','adminapi/index/index');
    //验证码图片
    Route::get('captcha/:id', "\\think\\captcha\\CaptchaController@index");//访问图片需要
    Route::get('captcha','adminapi/Login/captcha');
    Route::post('login','adminapi/Login/login');
    Route::get('logout','adminapi/Login/logout');
    //dan文件上传
    Route::post('logo','adminapi/Upload/logo');
    //duo文件上传
    Route::post('images','adminapi/Upload/images');
    Route::resource('categorys','adminapi/Category',[],['id'=>'\d+']);
    Route::resource('brands','adminapi/Brands',[],['id'=>'\d+']);
    Route::resource('types','adminapi/type',[],['id'=>'\d+']);
    Route::resource('goods','adminapi/goods',[],['id'=>'\d+']);
    Route::delete('delpics/:id','adminapi/goods/delpics',[],['id'=>'\d+']);
    Route::resource('auths','adminapi/Auth',[],['id'=>'\d+']);
    Route::get('nav','adminapi/Auth/nav');
    Route::resource('roles','adminapi/Role',[],['id'=>'\d+']);
    Route::resource('admins','adminapi/Admin',[],['id'=>'\d+']);
});
