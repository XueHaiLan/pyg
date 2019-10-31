<?php

namespace app\adminapi\controller;

use app\admin\model\Goods;
use app\common\model\Attribute;
use app\common\model\Spec;
use app\common\model\SpecValue;
use think\Controller;
use think\Db;
use think\Request;
use app\common\model\Type as ty;

class Type extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $ty=new ty();
        $res=$ty->field('id,type_name')->select();
        $this->yes($res);


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
            'type_name'=>'require',
            'spec'=>'require|array',
            'attr'=>'require|array'
        ]);
        if($validate!==true){
            $this->fault($validate);
        }
        Db::startTrans();
        try{
            //type表添加数据
            $type=ty::create(['type_name'=>$params['type_name']],true);
            foreach ($params['spec'] as $k=>$v){
                if(!empty($v)){
                    unset($params['spec'][$k]);
                    continue;
                }
                if(!is_array($v['value'])){
                    unset($params['spec'][$k]);
                    continue;
                }
                foreach ($v['value'] as $key=>$value){
                    if(!empty($value)){
                        unset($params['spec'][$k]['value'][$key]);
                    }
                }
                if(empty($params['spec'][$k]['value'])){
                    unset($params['spec'][$k]);
                    continue;
                }
            }
            $spec_date=[];
            foreach ($params['spec'] as $k=>$v){
                $spec_date[]=[
                    'type_id'=>$type['id'],
                    'spec_name'=>$v['name'],
                    'sort'=>$v['sort']
                ];
            }
            $spec=new Spec();
            $spec_res=$spec->saveAll($spec_date);
            //添加spec——value表数据
            $spec_value_date=[];
            foreach ($params['spec'] as $k=>$v){
                foreach ($v['value'] as $key=>$value){
                    $spec_value_date[]=[
                        'spec_id'=>$spec_res[$k]['id'],
                        'spec_value'=>$value,
                        'type_id'=>$type['id']
                    ];
                }
            }
            $spec_value=new SpecValue();
            $spec_value->saveAll($spec_value_date);
            //处理attr参数
            foreach ($params['attr'] as $k=>$v){
                if(empty($v['name'])){
                    unset($params['attr'][$k]);
                    continue;
                }
                if(!is_array($v['value'])){
                    $params['attr'][$k]['value']=[];
                }
                foreach ($v['value'] as $key=>$value){
                    if(empty($value)){
                        unset($params['attr'][$k]['value']['$key']);
                    }
                }
            }
            $attr_data=[];
            foreach ($params['attr'] as $k=>$v){
                $attr_data[]=[
                    'attr_name'=>$v['name'],
                    'type_id'=>$type['id'],
                    'attr_values'=>implode(',',$v['value']),
                    'sort'=>$v['sort'],
                ];
            }
            $attr=new Attribute();
            $attr->saveAll($attr_data);
            Db::commit();
            $this->yes($type);
        }catch (\Exception $e){
            Db::rollback();
            $msg=$e->getMessage();
            $line=$e->getLine();
            $file=$e->getFile();
            $this->fault('msg:'.$msg.'line:'.$line.'file:'.$file);
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
        $res=ty::with('specs,attrs,specs.spec_values')->find($id);
        if($res){
            $this->yes($res);
        }else{
            $this->fault('参数错误');
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
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'type_name|模型名称' => 'require',
            'spec|规格' => 'require|array',
            'attr|属性' => 'require|array'
        ]);
        if($validate !== true){
            $this->fail($validate);
        }

        /*$params结构参考：$params = [
            'type_name' => '手机123',
            'spec' => [
                ['name' => '颜色', 'sort'=>'50', 'value' => ['金色', '白色', '黑色']],
                ['name' => '内存', 'sort'=>'60', 'value' => ['64G', '128G', '256G']],
            ],
            'attr' => [
                ['name' => '产地', 'sort'=>'50', 'value' => ['国产', '进口']],
                ['name' => '重量', 'sort'=>'60', 'value' => []],
            ]
        ];*/
        //开启事务
        \think\Db::startTrans();
        try{
            //类型名称的修改
            $type = \app\common\model\Type::update(['type_name'=>$params['type_name']], ['id'=>$id], true);

            //对规格数据进行处理（去除无效的数据，比如空的值）
            foreach($params['spec'] as $k=>$v){
                if(empty($v['name'])){
                    //如果规格名称为空，删除整条数据
                    unset($params['spec'][$k]);
                    continue;
                }
                if(!is_array($v['value'])){
                    //如果规格值不是数组，删除整条数据
                    unset($params['spec'][$k]);
                    continue;
                }
                foreach($v['value'] as $key => $value){
                    if(empty($value)){
                        //去除 规格值数组中的空值
                        unset($params['spec'][$k]['value'][$key]);
                    }
                }
                //如果规格值数组是空数组，删除整条数据
                if(empty($params['spec'][$k]['value'])){
                    unset($params['spec'][$k]);
                    continue;
                }
            }
            //修改规格名称信息：先删除原来的数据，再新增新的数据
            \app\common\model\Spec::destroy(['type_id'=>$id]);
            //\app\common\model\Spec::where('type_id',$id)->delete();
            //组装数据
            $spec_data = [];
            foreach($params['spec'] as $v){
                $spec_data[] = [
                    'spec_name' => $v['name'],
                    'sort' => $v['sort'],
                    'type_id' => $id
                ];
            }
            $spec_model = new \app\common\model\Spec();
            $spec_res = $spec_model->saveAll($spec_data);
            /*$spec_res结构参考：$spec_res = [
                ['id' => 101, 'spec_name' => '颜色'],
                ['id' => 102, 'spec_name' => '内存'],
            ];*/
            //修改规格值信息：先删除原来的数据，再新增新的数据
            \app\common\model\SpecValue::destroy(['type_id'=>$id]);
            //组装数据
            $spec_value_data = [];
            foreach($params['spec'] as $k=>$v){
                //内层遍历 规格值数组
                foreach($v['value'] as $value){
                    $spec_value_data[] = [
                        'type_id' => $id,
                        'spec_id' => $spec_res[$k]['id'],
                        'spec_value' => $value
                    ];
                }
            }
            $spec_value_model = new \app\common\model\SpecValue();
            $spec_value_model->saveAll($spec_value_data);
            //处理属性信息（去除空的属性值）
            foreach($params['attr'] as $k=>$v){
                //如果属性名称为空，去除整条数据
                if(empty($v['name'])){
                    unset($params['attr'][$k]);
                    continue;
                }
                //如果属性值数组不是数组，设置为空数组
                if(!is_array($v['value'])){
                    $params['attr'][$k]['value'] = [];
                    continue;
                }
                //如果属性值为空的值，去除空的值
                foreach($v['value'] as $key => $value){
                    if(empty($value)){
                        unset($params['attr'][$k]['value'][$key]);
                        continue;
                    }
                }
            }
            //属性的修改：先删除原来的数据，再添加新的数据
            \app\common\model\Attribute::destroy(['type_id'=>$id]);
            $attr_data = [];
            foreach($params['attr'] as $k=>$v){
                $attr_data[] = [
                    'attr_name' => $v['name'],
                    'attr_values' => implode(',', $v['value']),
                    'type_id' => $id,
                    'sort' => $v['sort']
                ];
            }
            $attr_model = new \app\common\model\Attribute();
            $attr_model->saveAll($attr_data);
            //提交事务
            \think\Db::commit();
            $this->ok($type);
        }catch(\Exception $e){
            //回滚事务
            \think\Db::rollback();
            $msg = $e->getMessage();
            $this->fail($msg);
            //$this->fail('操作失败');
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
        Db::startTrans();
        try{
            $go=new Goods();
            $res=$go->where('type_id',$id)->count('id');
            if($res){
                throw new \Exception('商品模型下有商品，不能进行删除操作');
            }
            \app\common\model\Type::destroy(['id',$id]);
            Attribute::destroy(['type_id',$id]);
            Spec::destroy(['type_id',$id]);
            SpecValue::destroy(['type_id',$id]);
            Db::commit();
            $this->yes();
        }catch ( \Exception $e){
            Db::rollback();
            $msg=$e->getMessage();
            $line=$e->getLine();
            $file=$e->getFile();
            $this->fault('msg:'.$msg.'line:'.$line.'file:'.$file);
        }
    }
}
