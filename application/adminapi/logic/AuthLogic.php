<?php
namespace app\adminapi\logic;

class AuthLogic{
    public function check(){
        //权限检测
        $controller=request()->controller();
        $action=request()->action();
        $path=strtolower($controller.'/'.$action);
        if(in_array($path,['index/index','login/login','login/captcha'])){
            return true;
        }else{
            $params=input();
            $info=\app\common\model\admin::find($params['user_id']);
            if($info['role_id']==1){
                return true;
            }
            $res=\app\common\model\role::find($info['role_id']);
            $role_auth_ids=$res['role_auth_ids'];
            $role_auth_ids=explode(',',$role_auth_ids);
            $ret=\app\common\model\Auth::where('auth_c',$controller)->where('auth_a',$action)->find();
            if(in_array($ret['id'],$role_auth_ids)){
                return true;
            }
            return false;
        }
    }
}
