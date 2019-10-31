<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Exception;
use think\Request;
use tools\jwt\Token;

class BaseApi extends Controller
{
    //无需登录检测的方法
    protected $no_login=['login/login','login/captcha'];
    //处理跨域请求
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        header("Access-Control-Allow-Origin: http://localhost:8080");   //全域名
        header("Access-Control-Allow-Credentials: true");   //是否可以携带cookie

        header("Access-Control-Allow-Methods: POST,GET,PUT,OPTIONS,DELETE");   //允许请求方式
        header("Access-Control-Allow-Headers: X-Custom-Header");   //允许请求字段，由客户端决定
//        $this->checkLogin();

    }
    //响应
    public function response($code=200,$msg='success',$data=[]){
        $res=[
            'code'=>$code,
            'msg'=>$msg,
            'data'=>$data
        ];
        json($res)->send();
        die;
    }
    //成功响应
    public function yes($data=[],$code=200,$msg='success'){
        return $this->response($code,$msg,$data);
    }
    //失败响应
    public function fault($msg='error',$code=500){
        $this->response($code,$msg);
    }
    //登录验证
    public function checkLogin(Request $request=null)
    {
        try {
            $urls = strtolower(request()->controller() . '/' . request()->action());
            if (in_array($urls, $this->no_login)) {
                return;
            }
            $tokenid = Token::getUserId();
            if (!$tokenid) {
                $this->fault('请重新登陆');
            }
            $this->request->get(['user_id'=>$tokenid]);
            $this->request->post(['user_id'=> $tokenid]);
            $res=\app\adminapi\logic\AuthLogic::check();
            if(!$res){
                $this->fault('无权访问',402);
            }
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            $this->fault($msg . 'file:' . $file . 'line:' . $line);
        }
    }

}
