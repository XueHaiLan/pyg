<?php

namespace app\home\controller;

use app\common\model\Address;
use app\common\model\OrderGoods;
use app\common\model\PayLog;
use app\common\model\Spec;
use app\common\model\SpecGoods;
use app\home\logic\OrderLogic;
use BaconQrCode\Encoder\QrCode;
use think\Controller;
use think\Db;
use think\Exception;
use think\Validate;

class Order extends Base
{
    //订单页面数据显示
    public function create(){
        if(!session('?user_info')){
            //没登录不能访问页面，设置登录后返回的页面
            session('back_url','home/cart/index');
            $this->redirect('home/login/index');
        }
        $ids=session('user_info.id');
        $address=Address::where('user_id',$ids)->select();
        //调用方法查找购物车种选中的商品信息
        $res=\app\home\logic\OrderLogic::getCartWithGoods();
        //商品信息分为三部分展示
        $cart_data=$res['cart_data'];
        $total_price=$res['total_price'];
        $total_number=$res['total_number'];
//        dump($res);die();
        return view('create',['address'=>$address,'cart_data'=>$cart_data,'total_price'=>$total_price,'total_number'=>$total_number]);
    }
    //订单页面提交订单数据修改
    public function save(){
        //接收参数
        $params=input();
        $validate=$this->validate($params,[
            'address_id'=>'require|integer|gt:0'
        ]);
        if($validate!==true){
            $this->error($validate);
        }
        //获取用户id
        $user_id=session('user_info.id');
        //查询收货人信息
        $address=Address::where('id',$params['address_id'])->where('user_id',$user_id)->find();
        if(!$address){
            $this->error('收货数据显示异常');
        }
        Db::startTrans();
        try{

            //想订单表添加数据
            //订单编号
            $order_sn=time().mt_rand(100000,999999);
            //查询订单价格
            $res=OrderLogic::getCartWithGoods();
//            dump($res['cart_data']);die();
            foreach($res['cart_data'] as $v){
                if($v['number']>$v['goods']['goods_number']){
                    throw new \Exception('订单中包含库存不足的商品');
                }
            }

            $row=[
                'user_id'=>$user_id,
                'order_sn'=>$order_sn,
                'order_status'=>0,
                'consignee'=>$address['consignee'],
                'address'=>$address['area'].$address['address'],
                'phone'=>$address['phone'],
                'goods_price'=>$res['total_price'],//总价
                'shopping_price'=>0,//邮费
                'coupon_price'=>0,//优惠券
                'order_amount'=>$res['total_price'],//总价+邮费-优惠券
                'total_amount'=>$res['total_price']//总价+邮费
            ];
            $order=\app\common\model\Order::create($row,true);
            //向订单商品表添加多条数据
            $order_goods=[];
            foreach($res['cart_data'] as $v){
                    //$v 购物车表记录  $v['goods']  $v['spec_goods']
                $order_goods[] = [
                        'order_id' => $order['id'],
                        'goods_id' => $v['goods_id'],
                        'spec_goods_id' => $v['spec_goods_id'],
                        'number' => $v['number'],
                        'goods_name' => $v['goods']['goods_name'],
                        'goods_logo' => $v['goods']['goods_logo'],
                        'goods_price' => $v['goods']['goods_price'],
                        'spec_value_names' => $v['spec_goods']['value_names']
                    ];
                }

            $order_goods_model=new OrderGoods();
            $order_goods_model->saveAll($order_goods);
            //删除购物车中的对应记录
//        dump($user_id);die();
            \app\common\model\Cart::destroy(['user_id'=>$user_id,'is_selected'=>1]);
//            dump($a);die();
            $goods=[];
            $spec_goods=[];
            foreach ($res['cart_data'] as $k=>$v){
                if($v['spec_goods_id']){
                    $spec_goods[]=[
                        'id'=>$v['spec_goods_id'],
                        'store_count'=>$v['goods']['goods_number']-$v['number'],
                        'store_frozen'=>$v['goods']['frozen_number']+$v['number']
                    ];
                }else{
                    $goods[]=[
                        'id'=>$v['goods_id'],
                        'goods_number'=>$v['goods']['goods_number']-$v['number'],
                        'frozen_number'=>$v['goods']['frozen_number']+$v['number']
                    ];
                }
            }
            $goods_model=new \app\common\model\Goods();
            $goods_model->saveAll($goods);
            $spec_goods_model=new SpecGoods();
            $spec_goods_model->saveAll($spec_goods);
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            $mse=$e->getMessage();
            $line=$e->getLine();
            dump($mse.$line);die();
        }
        $this->redirect('home/order/pay?id='. $order['id']);
    }
    //立即支付页面
    public function pay($id){
        //查询订单信息
        $order=\app\common\model\Order::find($id);
        $pay_type=config('pay_type');//拿到配置文件中的数据
        return view('pay',['order'=>$order,'pay_type'=>$pay_type]);


        $url=url('/home/order/qrpay',['id'=>$order->order_sn,'debug'=>true],true,'http://pyg.com');
        //生成二维码支付
        $qrCoed=new \Endroid\QrCode\QrCode($url);
        //二维码保存路径
        $qr_path='/uploads/qrcode/'.uniqid(mt_rand(100000,999999),true).'png';

    }
    //去支付
    public function topay(){
        $params=input();
//        dump($params);die();
        //参数订单表id和支付方式
        $validate=$this->validate($params,[
           'id'=>'require|integer|gt:0',
            'pay_type'=>'require'
        ]);
        if($validate!==true){
            $this->error($validate);
        }
        $user_id=session('user_info.id');
        $order_data=\app\common\model\Order::where('id',$params['id'])->where('user_id',$user_id)->where('order_status',0)->find();
        if(!$order_data){
            $this->error('订单数据异常');
        }
        //支付方式修改到订单表
        $pay_type=config('pay_type');
        $pay_name=$pay_type[$params['pay_type']]['pay_name'];
        $order_data->pay_code=$params['pay_type'];
        $order_data->pay_name=$pay_name;
        $order_data->save();
        switch ($params['pay_type']){
            case 'wechat':
                //微信支付
                echo '微信支付开发中暂不支持';
                break;
            case 'unionpay':
                //银联支付
                echo '银联支付暂不支持';
                break;
            case 'alipay':
                //支付宝支付
                $html="<form id='alipayment' action='/plugins/alipay/pagepay/pagepay.php' method='post' style='display: none'>
    <input id='WIDout_trade_no' name='WIDout_trade_no' value='{$order_data['order_sn']}' />
    <input id='WIDsubject' name='WIDsubject' value='品优购商城订单'/>
    <input id='WIDtotal_amount' name='WIDtotal_amount' value='{$order_data['order_amount']}'/>
    <input id='WIDbody' name='WIDbody' value='品优购商城，商品'/>
</form><script>document.getElementById('alipayment').submit();</script>";
                echo $html;
                break;
            default:
                //支付宝
                break;
        }
    }
    //支付宝同步跳转页面
    public function calloback(){
        $params=input();
        //验证签名
        require_once("./plugins/alipay/config.php");
        require_once './plugins/alipay/pagepay/service/AlipayTradeService.php';


        $arr=$_GET;
        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check($params);
        if($result){
            //验证签名成功、跳转成功页面
            return view('paysuccess',['pay_names'=>'支付宝','total_amount'=>$params['total_amount']]);
        }else{
            //验证签名失败，跳转失败页面
            return view('payfail',['msg'=>'参数异常请重新支付或联系客服']);
        }
    }
    //异步通知地址，项目上线后可用
    public function notify(){
        require_once './plugins/alipay/config.php';
        require_once './plugins/alipay/pagepay/service/AlipayTradeService.php';

        $arr=$_POST;
        $alipaySevice = new AlipayTradeService($config);
        trace('order_nitify:异步通知开始；参数：'.json_encode($params,JSON_UNESCAPED_UNICODE),'debug');
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);
        if($result){
            //验证签名成功
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];
            if($trade_status=='TRADE_FINISHED'){
                echo 'success';die();
            }if($trade_status=='TRADE_SUCCESS'){
                $order=\app\common\model\Order::where('order_sn',$out_trade_no)->find();
                if($order){
                    trace('order_notify:订单不存在；订单编号：'.$out_trade_no,'error');
                    echo 'fail';die();
                }
                if($order['order_amount']!=$params['total_amount']){
                    trace('order_notify:订单支付金额不正确；订单编号:'.$out_trade_no.';应付金额：'.$order['order_amount'].';实际支付金额：'.$params['total_amount']);
                    echo 'fail';die();
                }
                //修改订单状态
                if($order['order_status']!=0){
                    trace('order_notify:订单状态不是代付款；订单编号：'.$out_trade_no.';订单状态：'.$order['order_status'],'error');
                    echo 'fail';die();
                }
                $order->order_status=1;//0代付款1已付款
                $order->save();
                //记录支付日志到数据表
                $pay_log=[
                    'order_sn'=>$out_trade_no,
                    'json'=>json_encode($params,JSON_UNESCAPED_UNICODE)
                ];
                PayLog::create($pay_log,true);
                //修改冻结库存
                $order_goods=OrderGoods::with('goods,spec_goods')->where('order_id',$order['id'])->select();
                $goods=[];
                $goods_spec=[];
                foreach($order_goods as $k=>$v){
                    if($v['spec_goods_id']){
                        //商品有SKU数据，修改SKU表数据
                        $goods_spec[]=[
                            'id'=>$v['spec_goods_id'],
                            'store_frozen'=>$v['spec_goods']['store_frozen']-$v['number']
                        ];
                    }else{
                        //商品没有SKU数据，修改goods表数据
                        $goods[]=[
                            'id'=>$v['goods_id'],
                            'frozen_number'=>$v['goods']['frozen_number']-$v['number']
                        ];
                    }
                }
                //批量修改两个表的数据
                $spec_goods_model=new SpecGoods();
                $spec_goods_model->saveAll($goods_spec);
                $goods_model=new \app\common\model\Goods();
                $goods_model->saveAll($goods);
                echo 'success';die();
            }
        }else{
            //验证失败
            echo 'fail';die();
        }
    }
}
