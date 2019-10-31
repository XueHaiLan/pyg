<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Image;
use think\Request;
use app\common\model\Category;
use app\common\model\Brand as Br;
use app\common\model\Goods;
use app\adminapi\controller\BaseApi;

class Brands extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $date=input();
        $where=[];
        if(!empty($date['cate_id'])){
            $where['cate_id']=$date['cate_id'];
            $br = new Br();
            $list=$br->where($where)->select();
        }else{
            if(isset($date['keyword']) && !empty($date['keyword'])){
                $keyword=$date['keyword'];
                $where['t1.name']=['like',"%{$keyword}%"];
            }
            $br=new Br();
            $list=$br->alias('t1')
                ->join('category t2','t1.cate_id=t2.id','left')
                ->where($where)
                ->field('t1.id,name,t1.logo,t1.desc,t1.sort,t1.is_hot,t2.cate_name')
                ->paginate(10);
        }
        $this->yes($list);
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
        $date=input();
        $validate=$this->validate($date,[
           'name'=>'require',
            'cate_id'=>'require|integer|gt:0',
            'is_hot'=>'require|in:0,1',
            'sort'=>'require|between:0,9999'
        ]);
        if($validate!==true){
            $this->fault($validate);
        }
        if(isset($date['logo'])&&!empty($date['logo'])&&is_file('.'.$date['logo'])){
            Image::open('.'.$date['logo'])->thumb(200,100)->save('.'.$date['logo']);
        }
        $res=Br::create($date,true);
        $data=Br::find($res['id']);
        $this->yes($data);
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
        $br=new Br();
        $ret=$br->where('id',$id)->find();
        if(!empty($ret)){
            $this->yes($ret);
        }
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
        $date=input();
        $validate=$this->validate($date,[
            'name'=>'require',
            'cate_id'=>'require|integer|gt:0',
            'is_hot'=>'require|in:0,1',
            'sort'=>'require|between:0,9999'
        ]);
        if($validate!==true){
            $this->fault($validate);
        }
        if(isset($date['logo'])&&!empty($date['logo'])&&is_file('.'.$date['logo'])){
            Image::open('.'.$date['logo'])->thumb(200,100)->save('.'.$date['logo']);
        }
        $res=Br::update($date,['id'=>$id],true);
        $data=Br::find($res['id']);
        $this->yes($data);
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
        $goods=new Goods();
        $ret=$goods->where('brand_id',$id)->count('id');
        if($ret){
            $this->fault('品牌下有商品，不能删除');
        }else{
            Br::destroy(['id'=>$id]);
            $this->yes();
        }
    }
}
