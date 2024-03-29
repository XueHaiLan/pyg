<?php
namespace app\home\logic;


use app\common\model\Cart;
use app\common\model\Goods;
use think\Collection;
use think\Controller;

class GoodsLogic extends Controller {
    public static function getGoodsWithSpecGoods($goods_id,$spec_goods_id=''){
        //SKU有记录就用SKU的
        if($spec_goods_id){
            $where['t2.id']=$spec_goods_id;
        }else{
            //如果没有就用goods的
            $where['t1.id']=$goods_id;
        }
        $goods=Goods::alias('t1')
            ->join('spec_goods t2','t1.id=t2.goods_id','left')
            ->field('t1.*,t2.id as spec_goods_id,t2.value_ids,t2.value_names,t2.price,t2.cost_price as cost_price2,t2.store_count,t2.store_frozen')
            ->where($where)
            ->find();
        if(!$goods) return [];
        //SKU表有值就用SKU表的，没有就用goods表的
        if($goods['price']>0){
            $goods['goods_price']=$goods['price'];
        }
        if($goods['cost_price2']>0){
            $goods['cost_price']=$goods['cost_price2'];
        }
        if($goods['store_count']){
            $goods['goods_number']=$goods['store_count'];
        }
        if($goods['store_frozen']){
            $goods['store_frozen']=$goods['store_frozen'];
        }
        return $goods->toArray();
    }
    //elasticsearch-7.1.0搜索逻辑部分
    public static function search(){
        //实例化ES工具类
        $es = new \tools\es\MyElasticsearch();
        //计算分页条件
        $keywords = input('keywords');
        $page = input('page', 1);
        $page = $page < 1 ? 1 : $page;
        $size = 10;
        $from = ($page - 1) * $size;
        //组装搜索参数体
        $body = [
            'query' => [
                'bool' => [
                    'should' => [
                        [ 'match' => [ 'cate_name' => [

                            'boost' => 2,     'query' => $keywords,
                            'boost' => 4, // 权重大
                        ]]],
                        [ 'match' => [ 'goods_name' => [
                            'query' => $keywords,
                            'boost' => 3,
                        ]]],
                        [ 'match' => [ 'goods_desc' => [
                            'query' => $keywords,
                        ]]],
                    ],
                ],
            ],
            'sort' => ['id'=>['order'=>'desc']],
            'from' => $from,
            'size' => $size
        ];
        //进行搜索
        $results = $es->search_doc('goods_index', 'goods_type', $body);
        //获取数据
        $data = array_column($results['hits']['hits'], '_source');
        $total = $results['hits']['total']['value'];
        //分页处理
        $list = \tools\es\EsPage::paginate($data, $size, $total, ['query'=>['keywords'=>$keywords]]);
        return $list;
    }
}