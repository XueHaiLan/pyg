<?php

namespace app\home\controller;

use app\common\model\Category;
use app\common\model\SpecValue;
use think\Controller;

class Goods extends Base
{
    //
//    public function index($id){
//
//        $res=\app\common\model\Goods::where('cate_id',$id)->order('id desc')->paginate(10);
//        $info=Category::where('id',$id)->find();
//        $cate_name=$info['cate_name'];
//        return view('index',['res'=>$res,'cate_name'=>$cate_name]);
//    }
    public function index($id=0)
    {
        //接收参数
        $keywords = input('keywords');
        if(empty($keywords)){
            //获取指定分类下商品列表
            if(!preg_match('/^\d+$/', $id)){
                $this->error('参数错误');
            }
            //查询分类下的商品
            $list = \app\common\model\Goods::where('cate_id', $id)->order('id desc')->paginate(10);
            //查询分类名称
            $category_info = \app\common\model\Category::find($id);
            $cate_name = $category_info['cate_name'];
        }else{
            try{
                //从ES中搜索
                $list = \app\home\logic\GoodsLogic::search();
//                dump($list);die();
                $cate_name = $keywords;
            }catch (\Exception $e){
                $this->error('服务器异常,或请开启ele');
            }
        }
        return view('index', ['list' => $list, 'cate_name' => $cate_name]);
    }
    public function detail($id){
        $goods=\app\common\model\Goods::with('spec_goods,goods_images')->find($id);
        if(!empty($goods['spec_goods'])){
            $goods['goods_price']=$goods['spec_goods'][0]['price'];
        }
//        dump($goods['spec_goods']);die();
        //查询所有相关的规格和规格值
        $value_ids=array_column($goods['spec_goods'],'value_ids');//去出第一个参数中的value_ids列
        $value_ids=implode('_',$value_ids);//将数组用_拼接成字符串
        $value_ids=explode('_',$value_ids);//把字符串按_分割成数组
        $value_ids=array_unique($value_ids);//去出数组中重复的元素
        $spec_values=SpecValue::with('spec_bind')->where('id','in',$value_ids)->select();
        $spec_values=collection($spec_values)->toArray();
        //将数组转换为容易页面展示的结构
        $spec=[];
        foreach ($spec_values as $v){
            $spec[$v['spec_id']]=[
                'id'=>$v['id'],
                'spec_name'=>$v['spec_name'],
                'spec_values'=>[]
            ];
        }
        foreach ($spec_values as $v){
            $spec[$v['spec_id']]['spec_values'][]=$v;
        }
//        dump($spec[19]['spec_values']['spec_value']);die();
        $value_ids_map=[];
        foreach ($goods['spec_goods'] as $v){
            $value_ids_map[$v['value_ids']]=[
                'id'=>$v['id'],
                'price'=>$v['price']
            ];
        }
        $value_ids_map=json_encode($value_ids_map);
//        dump($goods);die();
        return view('detail',['goods'=>$goods,'specs'=>$spec,'value_ids_map'=>$value_ids_map]);
    }
}
