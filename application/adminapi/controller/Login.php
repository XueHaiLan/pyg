<?php

namespace app\adminapi\controller;

use think\Controller;
use app\adminapi\controller\BaseApi;
use app\common\model\admin;
use tools\jwt\Token;


class Login extends BaseApi
{
    //验证码
    public function captcha(){
        //验证码标识
        $uniqid = uniqid(mt_rand(100000, 999999),true);
        //返回数据 验证码图片路径、验证码标识
        $data = [
            'src' => captcha_src($uniqid),
            'uniqid' => $uniqid
        ];
        $this->yes($data);
    }
    //登录
    public function login(){
////        echo enctypt_password('123');
        $date=input();
        $validate=$this->validate($date,[
           'username'=>'require',
           'password'=>'require',
           'code'=>'require',
            'uniqid'=>'require'
        ]);
        if(!$validate){
            $this->fault('数据不能为空');
        }
        if(!captcha_check($date['code'],$date['uniqid'])){
            $this->fault('验证码错误');
        }

        $admin=new admin;
        $ret=$admin->where('username',$date['username'])->find();
//        dump($ret->toArray());
        if(enctypt_password($date['password'])==$ret['password']){
            $data['token']=Token::getToken($ret['id']);
            $data['user_id']=$ret['id'];
            $data['username']=$ret['username'];
            $data['nickname']=$ret['nickname'];
            $data['email']=$ret['email'];
//            dump($data);
            $this->yes($data);
        }else{
            $this->fault('账号或密码错误');
        }

    }
    //退出
    public function logout(){
        $token=Token::getRequestToken();
        $delete_token=cache('delete_token')?:[];
        $delete_token=$token;
        cache('delete_token',$delete_token,86400);
        $this->yes();
    }
}
