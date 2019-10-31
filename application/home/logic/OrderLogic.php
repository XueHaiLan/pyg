<?php
namespace app\home\logic;
use app\common\model\Cart;
use think\Collection;





class OrderLogic{
    //查询购物车中对应的商品记录
    public static function getCartWithGoods(){
        $user_id=session('user_info.id');
        $cart_data=Cart::with('goods,spec_goods')->where('user_id',$user_id)->where('is_selected',1)->select();
        $cart_data=(new Collection($cart_data))->toArray();
//        dump($cart_data);die();
        $total_price=0;
        $total_number=0;
        foreach($cart_data as $k=>$v){
            if($v['spec_goods_id']){
                $cart_data[$k]['goods']['goods_price']=$v['spec_goods']['price'];
                $cart_data[$k]['goods']['cost_price']=$v['spec_goods']['cost_price'];
                $cart_data[$k]['goods']['goods_number']=$v['spec_goods']['store_count'];
                $cart_data[$k]['goods']['frozen_number']=$v['spec_goods']['store_frozen'];
            }
            $total_price+=$v['number']*$v['goods']['goods_price'];
            $total_number+=$v['number'];
        }
        return compact('cart_data','total_price','total_number');
    }
}