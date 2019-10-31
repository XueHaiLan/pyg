<?php

namespace app\common\model;

use think\Model;

class Goods extends Model
{
    //关联商品---商品模型表
    public function typeBind(){
        return $this->belongsTo('Type','type_id','id')->bind('type_name');
    }
    //关联商品---商品品牌表
    public function brandBind(){
        return $this->belongsTo('Brand','brand_id','id')->bind(['brand_name'=>'name']);
    }
    //关联商品---商品分类表
    public function categoryBind(){
        return $this->belongsTo('Category','cate_id','id')->bind('cate_name');
    }
    public function getGoodsAttrAttr($value){
        return json_decode($value,true);
    }
    public function categoryRow(){
        return $this->belongsTo('category','cate_id','id')->field('id_cate_name','pid_path_name');
    }
    public function brandRow(){
        return $this->belongsTo('Brand','brand_id','id');
    }
    public function type(){
        return $this->belongsTo('Type','type_id','id');
    }
    //商品--相册关联表
    public function goodsImages(){
        return $this->hasMany('GoodsImages','goods_id','id');
    }
    public function specGoods(){
        return $this->hasMany('SpecGoods','goods_id','id');
    }
    //同步商品文档
    protected static function init()
    {
        //实例化ES工具类
        $es = new \tools\es\MyElasticsearch();
        //设置新增回调
        self::afterInsert(function($goods)use($es){
            //添加文档
            $doc = $goods->visible(['id', 'goods_name', 'goods_desc', 'goods_price'])->toArray();
            $doc['cate_name'] = $goods->category->cate_name;
            $es->add_doc($goods->id, $doc, 'goods_index', 'goods_type');
        });
        //设置更新回调
        self::afterUpdate(function($goods)use($es){
            //修改文档
            $doc = $goods->visible(['id', 'goods_name', 'goods_desc', 'goods_price', 'cate_name'])->toArray();
            $doc['cate_name'] = $goods->category->cate_name;
            $body = ['doc' => $doc];
            $es->update_doc($goods->id, 'goods_index', 'goods_type', $body);
        });
        //设置删除回调
        self::afterDelete(function($goods)use($es){
            //删除文档
            $es->delete_doc($goods->id, 'goods_index', 'goods_type');
        });
    }
}
