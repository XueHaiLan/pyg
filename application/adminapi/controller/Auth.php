<?php

namespace app\adminapi\controller;

use app\common\model\role;
use think\Controller;
use think\Request;

class Auth extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $res=\app\common\model\Auth::select();
        $res=collection($res)->toArray();
        $params=input();
        if(isset($params['type'])&&$params['type']=='tree'){
            $res=get_tree_list($res);
        }else{
            $res=get_cate_list($res);
        }
        $this->yes($res);

    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
        $params=input();
        $validate=$this->validate($params,[
           'auth_name'=>'require',
           'pid'=>'require|integer|gt:0',
            'is_nav'=>'require|in:0,1'
        ]);
        if($validate!==true){
            $this->fault($validate);
        }
        if($params['pid']==0){
            $params['pid_path']=0;
            $params['level']=0;
        }else{
            $ret=\app\common\model\Auth::find($params['pid']);
            if(!$ret){
                $this->fault('数据异常');
            }
            $params['pid_path']=$ret['pid_path'].'_'.$ret['id'];
            $params['level']=$ret['level']+1;
        }
        $info=\app\common\model\Auth::create($params,true);
        $this->yes($info);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
        $res=\app\common\model\Auth::field('id,auth_name,pid,pid_path,auth_c,auth_a,is_nav,level')->find($id);
        $this->yes($res);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //

        $params=input();
        $validate=$this->validate($params,[
            'auth_name'=>'require',
            'pid'=>'require|integer|gt:0',
            'is_nav'=>'require|in:0,1'
        ]);
        if($validate!==true){
            $this->fault($validate);
        }
        if($params['pid']==0){
            $params['pid_path']=0;
            $params['level']=0;
        }else{
            $ret=\app\common\model\Auth::find($params['pid']);
            if(!$ret){
                $this->fault('数据异常');
            }
            $params['pid_path']=$ret['pid_path'].'_'.$ret['id'];
            $params['level']=$ret['level']+1;
            $info=\app\common\model\Auth::find($id);
            if($params['level']>$info['level']){
                $this->fault('不能进行降级');
            }
        }
        \app\common\model\Auth::update($params,['id'=>$id],true);
        $info=\app\common\model\Auth::find($id);
        $this->yes($info);

    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
        $res=\app\common\model\Auth::where('pid',$id)->count('id');
        if($res>0){
            $this->fault('权限下有子权限，不能进行删除');
        }
        \app\common\model\Auth::destroy($id);
        $this->yes();
    }
    public function nav(){
        $user_id=input('user_id');
        $res=\app\common\model\admin::find($user_id);
        if($res['role_id']==1){
            $info=\app\admin\model\Auth::where('is_nav',1)->select();
        }else{
            $ret=role::where('id',$res['role_id'])->find();
            $role=$ret['role_auth_ids'];
            $info=\app\common\model\Auth::where('id','in',$role)->where('is_nav',1)->select();
        }
        $info=collection($info)->toArray();
        $info=get_tree_list($info);
        $this->yes($info);
    }
}
