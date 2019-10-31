<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Role extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        //查询所有数据
//        $list = \app\common\model\role::select();
//        //查询所有权限
//        $auths = \app\common\model\Auth::select();
//        $auths = (new \think\Collection($auths))->toArray();
//        $new_auths = [];
//        foreach($auths as $k => $v){
//            $new_auths[$v['id']] = $v;
//        }
//        //遍历数组，对每一个角色 组装拥有的权限
//        foreach($list as $k=>$v){
//            if($v['id'] == 1){
//                //超级管理员
//                $list[$k]['role_auths'] = get_tree_list($auths);
//                continue;
//            }
//            //其他管理员
//            $role_auth_ids = explode(',', $v['role_auth_ids']);// [1,4,5,2,8]
//            $temp = [];//拥有的权限
//            //关联角色和权限数组
//            foreach($role_auth_ids as $id){
//                $temp[] = $new_auths[$id];
//            }
//            $list[$k]['role_auths'] = get_tree_list($temp);
//        }
//        //返回数据
//        $this->yes($list);



            $res=\app\common\model\role::select();
            foreach ($res as $k=>$v){
                if($v['id']==1){
                    //超管
                    $where=[];
                }else{
                    $where=['id'=>['in',$v['role_auth_ids']]];
                }
                $info=\app\common\model\Auth::where($where)->select();
//                $auth=new \app\common\model\Auth();
//                $info=$auth->where($where)->select();
                $info=(new \think\Collection($info))->toArray();
                $info=get_tree_list($info);
                $res[$k]['role_auths']=$info;

            }
            $this->yes($res);

//        $list = \app\common\model\role::select();
//        //遍历数组，对每一个角色 查询拥有的权限
//        foreach($list as $k=>$v){
//            if($v['id'] == 1){
//                //超级管理员
//                $where = [];
//            }else{
//                $where = ['id' => ['in', $v['role_auth_ids']]];
//            }
//            //查询单个角色下拥有的权限
//            $auth = \app\common\model\Auth::where($where)->select();
//            //转化为标准的二维数组
//            $auth = (new \think\Collection($auth))->toArray();
//            //转化数组结构为树状结构
//            $auth = get_tree_list($auth);
//            //放到结果数组$list中
//            $list[$k]['role_auths'] = $auth;
//        }
//
//        //返回数据
//        $this->yes($list);


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
//        $this->yes();
        $params=input();
        $validate=$this->validate($params,[
            'role_name'=>'require',
            'auth_ids'=>'require'
        ]);
        if($validate!==true){
            $this->fault($validate);
        }
        $params['role_auth_ids']=$params['auth_ids'];
        $info=\app\common\model\role::create($params,true);
        $info=\app\common\model\role::find($info['id']);
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
        $res=\app\common\model\role::find($id);
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
        if($id==1){
            $this->fault('超管不能删的');
        }
        $info=\app\common\model\admin::where('role_id',$id)->count('id');
        if($info>0){
            $this->fault('角色下有管理员，不能删的');
        }
        \app\common\model\role::destroy($id);
        $this->yes();
    }
}
