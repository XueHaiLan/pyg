<?php

namespace app\home\controller;

use app\common\model\Address;
use app\common\model\User;
use think\Controller;
use think\Request;
use app\home\controller\Base;
use think\View;

class PerCenter extends Base
{
    /**
     * 显示个人信息页面
     *
     * @return \think\Response
     */
    //设置基本信息
    public function message()
    {
        //
        $user_info=session('user_info');
        if($user_info['sex']=='男'){
            $user_info['sex']=1;
        }else{
            $user_info['sex']=2;
        }
        $user_info['birthday']=explode(',',$user_info['birthday']);
        $user_info['location']=explode(',',$user_info['location']);
//        dump($user_info);die();
        return view('seckillsetting-info',['user_info'=>$user_info]);
    }
    //上传头像
    public function upload(Request $request){
        // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('image');
//            dump($file);die();
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['size'=>1024*1024*10,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'upload');
        if($info){
            $user_info=session('user_info');
            // 成功上传后 获取上传信息
            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            $path= DS.'upload'.$info->getSaveName();
            User::where('id',$user_info['id'])->update(['header_protrait'=>$path]);
            $this->redirect('home/PerCenter/message');
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }

    }

    //安全设置
    public function safety(){
        $user_info=session('user_info');
        $phones=substr($user_info['phone'],0,3).'****'.substr($user_info['phone'],7);
        return view('newphone',['phone'=>$user_info['phone'],'phones'=>$phones]);
    }
    //发送验证码
    public function sendcode(){
        $params=input();
        $validate=$this->validate($params,[
            'phone'=>'require|regex:1[3-9]\d{9}'
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
            cache('register_time_'.$params['phone'],time(),5);
            return json(['code'=>200,'msg'=>'短信发送成功','data'=>$code]);//测试中显示$code
        }else{
            return json(['code'=>401,'msg'=>$res]);
        }
    }
    //绑定手机-验证身份下一步按钮
    public function phoneverify(){

        $params=input();
//        return $params;
        $validate=$this->validate($params,[
                'captcha|验证码'=>'require|captcha',
                'phonecode'=>'require'
        ]);
        if($validate!=true){
//            $this->error($validate);
            return ['code'=>500,'msg'=>'填写信息错误，请重新填写'];
        }
        if(empty($params['phonecode'])){
           return ['code'=>500,'msg'=>'请先填写验证码'];
        }
        $user_info=session('user_info');
//        return $user_info;
        $register_code=cache('register_code_'.$user_info['phone']);
//        return $register_code;
        if(!$register_code){
            return ['msg'=>'请先发送验证码' , 'code'=>500];
        }
        if($register_code!=$params['phonecode']){
            return ['code'=>500,'msg'=>'短信验证码错误，请重新填写'];
        }
        return ['code'=>'200','msg'=>'验证成功，请填写新的手机号'];
    }
    //绑定新手机号-下一步按钮
    public function newphone(){
        $params=input();
//        return $params;
        if(!isset($params['phone'])&&empty($params['phone'])){
            return ['code'=>500,'msg'=>'请填写手机号'];
        }
        $user_info=session('user_info');
        $register_code=cache('register_code_'.$params['phone']);
//        return $register_code;
        if(!$register_code){
            return ['msg'=>'请先发送验证码' , 'code'=>500];
        }

        if($register_code!=$params['code']){
            return ['code'=>500,'msg'=>'短信验证码错误，请重新填写'];
        }
//        return 123;

        $res=User::where('id',$user_info['id'])->update(['phone'=>$params['phone']]);
        if($res==1){
            $data=User::where('id',$user_info['id'])->find();
//            return $data;
            session('user_info',null);
            session('user_info',$data);
            $res=['code'=>200,'msg'=>'修改成功'];
        }else{
            $res=['code'=>500,'msg'=>'修改失败，请重新修改或联系管理员'];
        }
        return $res;
    }
    //修改密码
    public function modPassword(){
        $params=input();
//        dump($params);die();
        $validate=$this->validate($params,[
            'OldPassword'=>'require',
            'password'=>'require|egt:6',
            'confirm_password'=>'require|egt:6'
        ]);
        if($validate!=true){
            $this->error($validate);
        }
        if($params['password']!=$params['confirm_password']){
            $this->error('两次输入的密码不一致');
        }
        $params['OldPassword']=enctypt_password($params['OldPassword']);
        $user_info=session('user_info');
        if($params['OldPassword']==$user_info['password']){
            $params['password']=enctypt_password($params['password']);
            $res=User::where('id',$user_info['id'])->update(['password'=>$params['password']]);
        }
        if($res==1){
            echo ("<script type='text/javascript'>");
            echo ("alert('密码修改成功请重新登陆');");
            echo ("</script>");
            $login=new Login();
            $login->logout();
        }else{
            $this->error('密码修改失败，请重新修改或联系管理员');
        }
    }

    /**
     * 修改个人信息
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function savemassage(Request $request)
    {
        //接收数据
        $params=input();
//        dump($params);die();
        //校验数据
        $validate=$this->validate($params,[
            'sex'=>'integer',
            'select_year'=>'require',
            'select_month'=>'require',
            'select_day'=>'require',
            'city'=>'require',
            'district'=>'require',
            'nickname'=>'require',
            'province'=>'require',
        ]);
        if($validate!=true){
            $this->error($validate);
        }
        //将数据整合到data数组中
        $data=[];
        $data['nickname']=$params['nickname'];
        if(isset($params['sex']) && !empty($params['sex'])){
            if($params['sex']==1){
                $data['sex']='男';
            }else{
                $data['sex']='女';
            }
        }
        $data['profession']=$params['profession'];
        $data['location']=$params['province'].','.$params['city'].','.$params['district'];
        $data['birthday']=$params['select_year'].','.$params['select_month'].','.$params['select_day'];
        $user_info=session('user_info');
        //修改指定数据
        $res=User::where('id',$user_info['id'])->update($data,[],true);
        $user_data=User::find($user_info['id']);
        $user_data=$user_data->toArray();
        if($user_info==$user_data){
            $res=1;
        }
        //返回信息
        if($res==1){
            $ret=['code'=>200,'msg'=>'success','data'=>''];
            return $ret;
        }else{
            $ret=['code'=>500,'msg'=>'修改个人信息失败','data'=>''];
            return $data;
        }
    }
    //地址管理
    public function address(){
        $user_info=session('user_info');
        $res=Address::where('user_id',$user_info['id'])->select();
        $res=collection($res)->toArray();
        foreach ($res as $k=>$v){
            $res[$k]['phones']=substr($v['phone'],0,3).'****'.substr($v['phone'],7);
        }
//        dump($res);die();
        return view('seckillsetting-address',['site'=>$res]);
    }
    //地址新增
    public function sitesave(){
        $params=input();
        $validate=$this->validate($params,[
           'person'=>'require',
           'province'=>'require',
           'city'=>'require',
            'district'=>'require',
            'site'=>'require',
            'phone'=>'require',
        ]);
        if($validate!=true){
            $this->error($validate);
        }
        $user_info=session('user_info');
        $data=[
            'consignee'=>$params['person'],
            'area'=>$params['province'].' '.$params['city'].' '.$params['district'],
            'phone'=>$params['phone'],
            'address'=>$params['site'],
            'user_id'=>$user_info['id']
        ];
        $res=Address::create($data,[],true);
        if($res){
            $ret=['code'=>200,'msg'=>'添加成功'];
        }else{
            $ret=['code'=>500,'msg'=>'添加失败，请重新添加'];
        }
        return $ret;
    }
    //地址修改
    public function sitemodify(){
        $params=input();
//        return $params;
        $validate=$this->validate($params,[
            'person'=>'require',
            'province'=>'require',
            'city'=>'require',
            'district'=>'require',
            'site'=>'require',
            'phone'=>'require',
            'user_id'=>'require'
        ]);
        if($validate!=true){
            $this->error($validate);
        }
//        $user_info=session('user_info');
        $data=[
            'consignee'=>$params['person'],
            'area'=>$params['province'].' '.$params['city'].' '.$params['district'],
            'phone'=>$params['phone'],
            'address'=>$params['site'],
        ];
//        return $data;
        $res=Address::where('id',$params['user_id'])->update($data);
        if($res){
            $ret=['code'=>200,'msg'=>'修改成功'];
        }else{
            $ret=['code'=>500,'msg'=>'修改失败，请重新修改'];
        }
        return $ret;
    }
    //地址删除
    public function sitedie(){
        $params=input();
        $res=Address::destroy($params['id']);
        if($res){
            $ret= ['code'=>200,'msg'=>'删除成功'];
        }else{
            $ret= ['code'=>500,'msg'=>'删除失败'];
        }
        return $ret;
    }
    //设地址为默认
    public function defaults(){
        $params=input();
        $user_id=session('user_info.id');
        $res1=Address::where('user_id',$user_id)->where('is_default',1)->update(['is_default'=>0]);
        $res2=Address::where('id',$params['id'])->update(['is_default'=>1]);
        if($res1&&$res2){
            $ret=['code'=>200,'msg'=>'成功设为默认地址'];
        }else{
            $ret=['code'=>500,'msg'=>'设为默认地址失败'];
        }
        return $ret;
    }
}
