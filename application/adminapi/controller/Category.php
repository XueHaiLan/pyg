<?php

namespace app\adminapi\controller;

use app\common\model\Goods;
use app\common\model\Brand;
use app\common\model\Category as Cat;
use think\Controller;
use think\Image;
use think\Request;
use app\adminapi\controller\BaseApi;


class Category extends BaseApi
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
        $validate=$this->validate($date,[
            'pid'=>'integer|egt:0'
        ]);
        if($validate !==true){
            $this->fault($validate);
        }
        $where=[];
        if(isset($date['pid'])) {
            $where['pid'] = $date['pid'];
        }
        $cat=new Cat();
        $ret=$cat->field('id,cate_name,pid,pid_path_name,level,is_show,is_hot,image_url')->where($where)->select();
        $ret=collection($ret)->toArray();
        if(empty($date['type'])||$date['type']=='list'){
            $ret=get_cate_list($ret);
        }
        $this->yes($ret);
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
           'cate_name'=>'require',
            'pid'=>'require|integer|egt:0',
            'is_show'=>'require|in:0,1',
            'is_hot'=>'require|in:0,1',
            'sort'=>'require|between:0,999'
        ]);
        if($validate!==true){
            $this->fault($validate);
        }
        //添加顶级分类
        if($date['pid']==0){
            $date['level']=0;
            $date['pid_path']=0;
            $date['pid_path_name']='';
        }else{
            $cat=new Cat();
            $ret=$cat->where('id',$date['pid'])->find();
            $date['level']=$ret['level']+1;
            $date['pid_path']=$ret['pid_path'].'_'.$ret['id'];
            $date['pid_path_name']=$ret['pid_path_name']?$ret['pid_path_name'].'_'.$ret['cate_name']:$ret['cate_name'];
        }
        if(isset($date['logo'])&&file_exists('.'.$date['logo'])){
            $image=Image::open('.'.$date['logo']);
            $image->thumb(50,50)->save('.'.$date['logo']);
            $date['image_url']=$date['logo'];
        }
        $cat=new Cat();
        $data=Cat::create($date,true);
        $info=$cat->find($data['id']);
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
        $cat=new Cat();
        $ret=$cat->field('id,cate_name,pid,pid_path_name,level,is_show,is_hot,image_url')->where('id',$id)->select();
        if($ret){
            $this->yes($ret);
        }else{
            $this->fault('请求错误');
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
        $cat=new Cat();
        $ret=$cat->where('id',$id)->find();
        $this->yes($ret);
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
        $date=input();
        $validate=$this->validate($date,[
            'cate_name'=>'require',
            'pid'=>'require|integer|egt:0',
            'is_show'=>'require|in:0,1',
            'is_hot'=>'require|in:0,1',
            'sort'=>'require|between:0,999'
        ]);
        if($validate!==true){
            $this->fault($validate);
        }

        if($date['pid']==0){
            $date['pid_path']=0;
            $date['level']=0;
            $date['pid_path_name']='';
        }else{
            $cat=new Cat();
            $ret=$cat->where('pid',$date['pid'])->find();
//            if(!$ret){
//                $this->fault('数据异常');
//            }
            $date['pid_path']=$ret['pid_path'].'_'.$ret['id'];
            $date['level']=$ret['level']+1;
            $date['pid_path_name']=$ret['pid_path_name']?$ret['pir_path_name'].'_'.$ret['cate_name']:$ret['cate_name'];
            $ret2=$cat->where('id',$id)->find();
            if($ret2['level']<$date['level']){
                $this->fault('不能降级');
            }
            if(!empty($date['logo'])&&file_exists('.'.$date['logo'])){
                $image=Image::open('.'.$date['logo']);
                $image->thumb(50,50)->save('.'.$date['logo']);
                $date['image_url']=$date['logo'];
            }

        }
        $cat=new Cat();
//        dump($cat);die;

        $res = Cat::update($date,['id'=>$id],true);
        $ret3=$cat->field('id,cate_name,pid,pid_path_name,level,is_show,is_hot,image_url')->where('id',$res['id'])->find();
        $this->yes($ret3);

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
        $cat=new Cat();
        $ret=$cat->where('pid',$id)->count('id');
        if($ret){
            $this->fault('分类下有子分类，不能删除');
        }
        $goods=new Goods();
        $ret1=$goods->where('cate_id')->count('id');
        if($ret1){
            $this->fault('分类下有商品，不能删除');
        }
        $brand=new Brand();
        $ret2=$brand->where('cate_id')->count('id');
        if($ret2){
            $this->fault('分类下有品牌，不能删除');
        }
        $cat->where('id',$id)->delete();
        $this->yes();
    }
}
