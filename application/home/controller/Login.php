<?php

namespace app\home\controller;

use app\common\model\OpenUser;
use app\common\model\User;
use think\Controller;
use think\Request;
use think\View;

class Login extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //登录页面
        $this->view->engine->layout(false);
        return view();
    }
    public function register(){
        //注册页面
        $this->view->engine->layout(false);
        return view();
    }
    //登录页面登录按钮
    public function dologin(){
        //登录检测
        $params=input();
        $validate=$this->validate($params,[
           'username|用户名'=>'require',
           'password|密码'=>'require|length:6,30'
        ]);
        if($validate!==true){
            $this->error($validate);
        }
        $lognum=cache($params['username']);
        if($lognum>4){
            $this->error('账户存在风险，请稍后登录');
        }
        //密码加密
        $params['password']=enctypt_password($params['password']);
        //匹配用户数据
        $res=User::where(function ($query)use ($params){
            $query->where('phone',$params['username'])->whereOr('email',$params['username'])->whereOr('username',$params['username']);
        })->where('password',$params['password'])->find();
        if(!$res){
            cache($params['username'],$lognum+1,100);
            $this->error('用户名或密码不正确');
        }
        session('user_info_id',$res['id']);
        \app\home\logic\CartLogic::cookieToDb();
        //关联第三方用户
        $open_user_id=session('open_user_id');
        if($open_user_id){
            OpenUser::update(['user_id'=>$res['id']],['id'=>$open_user_id],true);
            session('open_user_id',null);
        }
        $nickname=session('open_user_nickname');
        if($nickname){
            User::update(['nickname'=>$nickname],['id'=>$res['id']],true);
            session('open_user_nickname',null);
        }
        $res=User::find($res['id']);
        //存到session中

        session('user_info',$res->toArray());
        $url=session('back_url')?: 'home/index/index';
        $this->redirect($url);
    }
    //首页退出按钮
    public function logout(){
        //清空session
        session('user_info',null);
        $this->redirect('home/login/index');
    }
    //注册页面注册按钮
    public function phone(){
        //注册页面提交
        $params=input();
        $validate=$this->validate($params,[
            'phone'=>'require|regex:1[3-9]\d{9}|unique:user,phone',
            'code'=>'require',
            'password'=>'require|length:6,100',
            'repassword'=>'require|confirm:password'
        ]);
        if($validate!==true){
            $this->error($validate);
        }
        //拿到验证码
        $code=cache('register_code_'.$params['phone']);
        if($code!=$params['code']){
            $this->error('验证码错误');
        }
        //清空验证码
        cache('register_code_'.$params['phone'],$code,null);
        //把手机号转换为昵称
        $nickname=substr($params['phone'],0,3).'****'.substr($params['phone'],7);
        $params['username']=$params['phone'];
        $params['nickname']=$nickname;
        $params['password']=enctypt_password($params['password']);
        //提交表单
        User::create($params,true);
        $this->success('注册成功','home/index/index');
    }
    //发送验证码按钮
    public function sendcode(){
        $params=input();
        $validate=$this->validate($params,[
            'phone'=>'require|regex:1[3-9]\d{9}|unique:user,phone'
        ]);
        if($validate!==true){
            return json(['code'=>'401','msg'=>"$validate"]);
        }
        $last_time=cache('register_time_'.$params['phone']);
        if($last_time){
            $res=[
                'code'=>500,
                'msg'=>'验证码发送太频繁'
            ];
            echo json_encode($res);die();
        }
        $code=mt_rand(1000,9999);
//        $res=send_msg($params['phone'],'【创信】你的验证码是：'.$code.'，3分钟内有效！');
        $res=true;
        if($res===true){
            cache('register_code_'.$params['phone'],$code,180);
            cache('register_time_'.$params['phone'],time(),60);
            return json(['code'=>200,'msg'=>'短信发送成功','data'=>$code]);//测试中显示$code
        }else{
            return json(['code'=>401,'msg'=>$res]);
        }
    }

    //qq登录回调地址
    public function qqcallback(){
        require_once("./plugins/qq/API/qqConnectAPI.php");
        $qc = new \QC();
        $access_token=$qc->qq_callback();//接口调用过程中的临时令牌
        $openid=$qc->get_openid();//第三方账号在本应用中的唯一标识
        //获取用户信息
        $qc=new \QC($access_token,$openid);
        $info=$qc->get_user_info();
//        dump($info);die();
        //自动登录注册流程
        //判断账号是否关联用户
        $open_user=OpenUser::where('open_type','qq')->where('openid',$openid)->find();
        if($open_user&&$open_user['id']){
            //用户已经进行过关联，登录成功
            $user=User::find($open_user['id']);
            $user->nickname=$info['nickname'];
            $user->save();
            //数据存到session中头部使用
            session('user_info',$user->toArray());
            $this->redirect('home/index/index');
        }
        if(!$open_user){
            $open_user=OpenUser::create(['open_type'=>'qq','openid'=>$openid]);
        }
        session('open_user_id',$open_user['id']);
        session('open_user_nickname',$info['nickname']);
        $this->redirect('home/login/index');
    }
    //支付宝登录回调地址
    public function alicallback(){
        require_once ('./plugins/oauth/service/AlipayOauthService.php');
        require_once ('./plugins/oauth/config.php');
        $AlipayOauthService=new \AlipayOauthService($config);
        //获取用户auth_code
        $auth_code=$AlipayOauthService->auth_code();
        //获取用户access_token
        $access_token=$AlipayOauthService->get_token($auth_code);
        //获取用户信息
        $info=$AlipayOauthService->get_user_info($access_token);
        $openid=$info['user_id'];

        //关联绑定用户
        $open_user=OpenUser::where('open_type','alipay')->where('openid',$openid)->find();
        if($open_user&&$open_user['user_id']){
            //已经关联用户，直接登录
            $user=User::find($open_user['user_id']);
            $user->nickname=$info['nick_name'];
            $user->save();
            session('user_info',$user->toArray());
            $this->redirect('home/index/index');
        }
        if(!$open_user){
            $open_user=OpenUser::create(['open_type'=>'alipay','openid'=>$openid]);
        }
        session('open_user_id',$open_user['id']);
        session('open_user_nickname',$info['nick_name']);
        $this->redirect('home/login/index');
    }

}
