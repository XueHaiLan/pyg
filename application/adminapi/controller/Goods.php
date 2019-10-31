<?php

namespace app\adminapi\controller;

use app\common\model\GoodsImages;
use app\common\model\SpecGoods;
use think\Controller;
use think\Db;
use think\Image;
use think\Request;

class Goods extends BaseApi
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
            $where['goods_name']=['like',"%{$params['keyword']}%"];
        }
        $list=\app\common\model\Goods::with('type_bind,brand_bind,category_bind')->where($where)->paginate(10);
        if($list){
            $this->yes($list);
        }else{
            $this->fault('参数错误');
        }
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
           'goods_name'=>'require',
           'goods_remark'=>'require',
           'cate_id'=>'require|integer',
           'brand_id'=>'require|integer|gt:0',
           'goods_price'=>'require',
           'market_price'=>'require',
           'cost_price'=>'require',
           'goods_logo'=>'require',
            'is_free_shipping'=>'require|in:0,1',
            'goods_images'=>'require|array',
            'type_id'=>'require',
            'item'=>'require',
            'attr'=>'require'
        ]);
        if($validate!==true){
            $this->fault($validate);
        }
        if(!file_exists('.'.$params['goods_logo'])){
            $this->fault('logo图片不存在');
        }
        Db::startTrans();
        try{
            //添加商品数据
            $goods_logo=dirname($params['goods_logo']).DS.'thumb_'.basename($params['goods_logo']);
            Image::open('.'.$params['goods_logo'])->thumb(200,200)->save('.'.$goods_logo);
            $params['goods_logo']=$goods_logo;
            $params['goods_attr']=json_encode($params['attr'],JSON_UNESCAPED_UNICODE);
            $goods=\app\common\model\Goods::create($params,true);
            //添加商品图片数据
            $goods_images=[];
            foreach ($params['goods_images'] as $k=>$v){
                $pic_big=dirname($v).DS.'thumb_800_'.basename($v);
                $pic_sma=dirname($v).DS.'thumb_400_'.basename($v);
                $image=Image::open('.'.$v);
                $image->thumb(800,800)->save('.'.$pic_big);
                $image->thumb(400,400)->save('.'.$pic_sma);
                $goods_image[]=[
                  'goods_id'=>$goods['id'],
                    'goods_big'=>$pic_big,
                    'goods_sma'=>$pic_sma
                ];
            }
            $goodsimage=new GoodsImages();
            $goodsimage->saveAll($goods_images);
            //添加specgoods表数据
            $spec_goods=[];
            foreach ($params['item'] as $k=>$v){
                $v['goods_id']=$goods['id'];
                $spec_goods[]=$v;
            }
            $specgoods=new SpecGoods();
            $specgoods->saveAll($spec_goods);
            Db::commit();
            $info=\app\common\model\Goods::with('type_bind,brand_bind,category_bind')->find($goods['id']);
            $this->yes($info);

        }catch (\Exception $e){
            Db::rollback();
            $msg=$e->getMessage();
            $line=$e->getLine();
            $file=$e->getFile();
            $this->fault(',msg:'.$msg.'line:'.$line.'file:'.$file);
        }


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
        $info=\app\common\model\Goods::with('category_row,brand_row,goods_images,spec_goods')->find($id);
        $info['category']=$info['category_row'];
        unset($info['category_row']);
        $info['brand']=$info['brand_row'];
        unset($info['brand_row']);

        $type=\app\common\model\Type::with('specs,specs.spec_values,attrs')->find($info['type_id']);
        $info['type']=$type;
        $this->yes($info);
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
        $info=\app\common\model\Goods::with('category,category.brands,goods_images,spec_goods')->find($id);
        $type=\app\common\model\Type::with('attrs,specs,specs.spec_values')->find($info['type_id']);
        $info['type']=$type;
        $types=\app\common\model\Type::field('id,type_name')->select();
        $category_one=\app\common\model\Category::where('pid',0)->select();
        $temp=explode('_',$info['category']['pid_path']);
        $category_two=\app\common\model\Category::where('pid',$temp[1])->select();
        $category_three=\app\common\model\Category::where('pid',$temp[2])->select();
        $date=[
          'goods'=>$info,
          'category'=>[
              'cate_one'=>$category_one,
              'cate_two'=>$category_two,
              'cate_three'=>$category_three
          ]  ,
            'type'=>$types
        ];
        $this->yes($date);
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
        $params=input();
        $validate=$this->validate($params,[
           'goods_name'=>'require',
           'foods_priec'=>'require',
           'goods_images'=>'array',
           'item'=>'array',
           'attr'=>'array'
        ]);
        if($validate!==true){
            $this->fault($validate);
        }
        Db::startTrans();
        try{
            if(!empty($params['goods_logo'])&&file_exists('.'.$params['goods_logo'])){
                $goods_logo=dirname($params['goods_logo']).DS.'thumb_'.basename($params['goods_logo']);
                Image::open('.'.$params['goods_logo'])->thumb(200,240)->save('.'.$goods_logo);
                $params['goods_logo']=$goods_logo;
            }
            if(isset($params['attr'])){
                $params['goods_attr']=json_encode($params['attr'],JSON_UNESCAPED_UNICODE);
            }
            \app\common\model\Goods::update($params,['id'=>$id],true);
            if(isset($params['goods_images'])){
                $goods_images=[];
                foreach($params['goods_iamges'] as $v){
                    if(!is_file('.'.$v)){
                        continue;
                    }
                    $pics_big=dirname($v).DS.'thumb_800_'.basename($v);
                    $pics_sma=dirname($v).DS.'thumb_400_'.basename($v);
                    $image=Image::open('.'.$v);
                    $image->thumb(800,800)->save('.'.$pics_big);
                    $image->thumb(400,400)->save('.'.$pics_sma);
                    $goods_images[]=['goods_id'=>$id,'prcs_big'=>$pics_big,'pics_sma'=>$pics_sma];
                }
                $goods_images_model=new GoodsImages();
                $goods_images_model->saveAll($goods_images);
                if(isset($params['item'])&&!empty($params['item'])){
                    SpecGoods::destroy(['goods_id'=>$id]);
                    $spec_goods=[];
                    foreach ($params['item'] as $v){
                        $v['goods_id']=$id;
                        $spec_goods[]=$v;
                    }
                    $spec_goods_model=new SpecGoods();
                    $spec_goods_model->allowField(true)->saveAll($spec_goods);
                }
            }
            Db::commit();
            $info=\app\common\model\Goods::with('category_bind,brand_bind,type_bind')->find($id);
            $this->yes($info);
        }catch (\Exception $e){
            Db::rollback();
            $msg=$e->getMessage();
            $line=$e->getLine();
            $file=$e->getFile();
            $this->fault('msg:'.$msg.'file:'.$file.'line:'.$line);
        }
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
        $is_on_sale=\app\common\model\Goods::where('id',$id)->value('is_on_sale');
        if($is_on_sale==1){
            $this->fault('商品已上架，清先下架再删除');
        }
        $goods_images=GoodsImages::where('goods_id',$id)->select();
        GoodsImages::destroy(['goods_id'=>$id]);
        $temp=[];
        foreach ($goods_images as $k=>$v){
            $temp[]=$v['pics_big'];
            $temp[]=$v['pics_sam'];
        }
        foreach ($temp as $v){
            if(file_exists('.'.$v)){
                unlink('.'.$v);
            }
        }
        $this->yes();
    }
    public  function delpics($id){
        $info=GoodsImages::find($id);
        if(!$info){
            $this->yes();
        }
        $info->delete();
        if(file_exists($info['pics_big'])){
            unlink('.'.$info['pics_big']);
        }
        if(file_exists($info['pics_sma'])){
            unlink('.'.$info['pics_sma']);
        }
        $this->yes();
    }
}
