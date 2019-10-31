<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Admin extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $params=input();
        $where=[];
        if(isset($params['keyword'])&&!empty($params['keyword'])){
            $where['username']=['like',"%{$params['keyword']}%"];
        }
        $info=\app\common\model\admin::alias('t1')
            ->where($where)
            ->join('role t2','t1.role_id=t2.id','left')
            ->field('t1.*,t2.role_name')
            ->order('id desc')
            ->paginate(10);
        $this->yes($info);
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
            'username'=>'require',
            'email'=>'require|email',
            'role_id'=>'require',

        ]);
        if($validate!==true){
            $this->fault($validate);
        }
        if(!isset($params['password'])||empty($params['password'])){
            $params['password']='123456';
        }
        $info=\app\common\model\admin::create($params,true);
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
        $res=\app\common\model\admin::find($id);
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
        if($id==1){
            $this->fault('不能修改超管的资料');
        }
        $params=input();
        if(isset($params['type'])&&$params['type']=='reset_pwd'){
            $params['password']='123456';
        }else{
            $validate=$this->validate($params,[
                'username'=>'length:1,20',
                'email'=>'email',
                'role_id'=>'integer|gt:0',
            ]);
            if($validate!==true){
                $this->fault($validate);
            }
            unset($params['password']);
        }
        unset($params['username']);
        \app\common\model\admin::update($params,['id'=>$id],true);
        $info=\app\common\model\admin::find($id);
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
        if($id==1){
            $this->fault('无权删除管理员');
        }
        $res=\app\common\model\admin::find($id);
        if(!res){
            $this->yes();
        }
        if($res['role_id']==1){
            $this->fault('无权删除管理员');
        }
        if($id==input('user_id')){
            $this->fault('不能删除自己的啊');
        }
        \app\common\model\admin::destroy($id);
        $this->yes();

    }
}
