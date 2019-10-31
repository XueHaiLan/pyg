<?php
namespace app\home\logic;
use app\common\model\Cart;
use app\common\model\Category;
use think\Collection;


class CartLogic{
    //加入购物车
    public static function addCart($goods_id,$spec_goods_id='',$number=1,$is_selected=1){
        if(session('?user_info')){
            //已经登录，添加数据到购物车
            $user_id=session('user_info_id');
//            dump($user_id);die();
            $where=[
                'user_id'=>$user_id,
                'goods_id'=>$goods_id,
                'spec_goods_id'=>$spec_goods_id
            ];
            $info=Cart::where($where)->find();
            if($info){
                //数据存在，增加数量
                $info->number+=$number;
                $info->is_selected=$is_selected;
                $info->save();
            }else{
                //数据不存在，添加新商品
                $where['number']=$number;
                $where['is_selected']=$is_selected;
                Cart::create($where,true);
            }
        }else{
            //还没登录，添加数据到cookie
            $data=cookie('cart')?:[];
            $key=$goods_id.'_'.$spec_goods_id;
            //判断是否存在相同的记录
            if(isset($data[$key])){
                //存在则累加数量
                $data[$key]['number']+=$number;
                $data[$key]['is_selected']=$is_selected;
            }else{
                $data[$key]=[
                  'id'=>$key,
                  'goods_id'=>$goods_id,
                  'spec_goods_id'=>$spec_goods_id,
                  'is_selected'=>$is_selected,
                  'number'=>$number
                ];
                cookie('cart',$data,7*86400);
            }
        }
    }
    //查询购物记录
    public static function getAllCart(){
        if(session('?user_info')){
            //登录用户从数据表中取数据
            $user_id=session('user_info.id');
            $data=Cart::field('id,user_id,goods_id,spec_goods_id,number,is_selected')->where('user_id',$user_id)->select();
            $data=(new Collection($data))->toArray();
        }else{
            //未登录用户从cookie中取数据
            $data=cookie('cart')?:[];
            $data=array_values($data);
        }
        return $data;
    }
    //登陆时从cookie迁移到数据库中的购物车数据
    public static function cookieToDb(){
        $data=cookie('cart')?:[];
        foreach($data as $k=>$v){
            self::addCart($v['goods_id'],$v['spec_goods_id'],$v['number']);
        }
        cookie('cort',null);
    }
    //修改商品购买数量
    public static function changeNum($id,$number){
        if(session('user_info.id')){
            $user_id=session('user_info.id');
            Cart::update(['number'=>$number],['id'=>$id,'user_id'=>$user_id]);
        }else{
            $data=cookie('cart')?:[];
            $data[$id]['number']=$number;
            cookie('cart',$data,86400*7);
        }
    }
    //删除购物车中的记录
    public static function delCart($id){
        if(session('?user_info')){
            //一登录删除数据库中的数据
            $user_id=session('user_info.id');
            Cart::where(['user_id'=>$user_id,'id'=>$id])->delete();
        }else{
            //未登录删除cookie中的数据
            $data=cookie('cart')?:[];
            unset($data[$id]);
            cookie('cart',$data,86400*7);
        }
    }
    //修改购物车中的选中状态
    public static function changeStatus($id,$status){
        if(session('?user_info')){
            //已经登录修改购物车中的数据
            $user_id=session('user_info.id');
            $where['user_id']=$user_id;
            if($id!='all'){
                $where['id']=$id;
            }
            Cart::where($where)->update(['is_selected'=>$status]);
        }else{
            //为登录修改cookie中的数据
            $data=cookie('cart')?:[];
            if($id=='all'){
                foreach ($data as &$v){
                    $v['is_selected']=$status;
                }
                unset($v);
            }else{
                $data[$id]['is_selected']=$status;
            }
            cookie('cart',$data,86400*7);
        }
    }
}