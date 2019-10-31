<?php

namespace app\home\controller;

use app\common\model\Category;
use app\home\logic\CartLogic;
use think\Collection;
use think\Controller;

class Cart extends Base
{
    //
    public function index(){
        $list=CartLogic::getAllCart();
//        dump($list);die();
        foreach ($list as &$v){
            $v['goods']=\app\home\logic\GoodsLogic::getGoodsWithSpecGoods($v['goods_id'],$v['spec_goods_id']);
            $v['goods']=(new Collection($v['goods']))->toArray();
        }
        unset($v);
//        dump($list);die();
        return view('index',['list'=>$list]);
    }
    public function addCart(){
        //只能是post请求
        if(request()->isGet()){
            $this->redirect('hoem/index/index');
        }
        //接收参数
        $params=input();
//        dump($params);die();
        $validate=$this->validate($params,[
           'goods_id'=>'require|integer|gt:0',
           'spec_goods_id'=>'integer|gt:0',
           'number'=>'require|integer|egt:0'
        ]);
        if($validate!==true){
            $this->error($validate);
        }
        //添加数据到购物车
        \app\home\logic\CartLogic::addCart($params['goods_id'],$params['spec_goods_id'],$params['number']);
        //展示成功结果页面
        $goods=\app\home\logic\GoodsLogic::getGoodsWithSpecGoods($params['goods_id'],$params['spec_goods_id']);


        //可能需要栏的数据
        //根据goods_id查询商品和对应的分类数据
        $res=\app\common\model\Goods::with('category_row')->find($params['goods_id']);
        //查询分类下的其他商品

        return view('addcart',['goods'=>$goods]);
    }
    public function tuijian($goods_id){
        //从goods表中查出cate_id
        $cate_id=\app\common\model\Goods::where('id',$goods_id)->value('cate_id');
        //在自己的三级分类下查询相似商品
        $data=\app\common\model\Goods::where('cate_id',$cate_id)->where('id','<>',$goods_id)->select();
        $data=(new Collection($data))->toArray();
        $length=count($data);
        if($length<24){
            //查询商品的上级分类
            $pid_path=Category::where('id',$cate_id)->value('pid_path');
            $pid_temp=explode('_',$pid_path);
            //查找上级分类的商品
            $cate_ids=Category::where('pid',$pid_temp[2])->where('id','<>',$cate_id)->column('id');
            $data1=\app\common\model\Goods::where('cate_id','in',$cate_ids)->limit(24-$length)->select();
            $data=array_merge($data,$data1);
            $length2=count($data);
            if($length2<24){
                //查询所属一级分类下的二级分类
                $cate_two_ids=Category::where('pid',$pid_temp[1])->where('id','<>',$pid_temp[2])->column('id');
                $cate_three_ids=Category::where('pid','in',$cate_two_ids)->column('id');
                $data2=Category::where('cate_id','in',$cate_three_ids)->limit(24-$length2)->select();
                $data=array_merge($data,$data2);
                $length3=count($data);
                $ids=array_column($data,'id');
                if($length3<24){
                    $data3=\app\common\model\Goods::where('id','not in',$ids)->limit(24-$length3)->select();
                    $data=array_merge($data,$data3);
                }
            }
        }
        return $data;
    }
    //购物车中商品数量改变事件
    public function changenum(){
//        dump(123);die();
        $params=input();
        $validate=$this->validate($params,[
           'number'=>'require|integer|gt:0',
           'id'=>'require'
        ]);
        if($validate!==true){
            $res=['msg'=>'参数错误','code'=>400];
            echo json_encode($res);die();
        }
        \app\home\logic\CartLogic::changeNum($params['id'],$params['number']);
        $res=['code'=>200,'msg'=>'success'];
        echo json_encode($res);die();
    }
    public function delcart(){
        $params=input();
        if(!isset($params['id'])&&empty($params['id'])){
            $res=['code'=>400,'msg'=>'参数错误'];
            echo json_encode($res);die();
        }
        \app\home\logic\CartLogic::delCart($params['id']);
        $res=['code'=>200,'msg'=>'success'];
        echo json_encode($res);die();
    }
    //修改选中状态
    public function chagestatus(){
        $params=input();
        $validate=$this->validate($params,[
           'id'=>'require',
           'status'=>'require|in:0,1'
        ]);
        if($validate!==true){
            $res=['code'=>400,'msg'=>$validate];
            echo json_encode($res);die();
        }
        \app\home\logic\CartLogic::changeStatus($params['id'],$params['status']);
        $res=['code'=>200,'msg'=>'success'];
        echo json_encode($res);die();
    }
}
