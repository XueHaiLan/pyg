<?php

namespace app\adminapi\controller;

use think\Controller;
use app\adminapi\controller\BaseApi;

class Upload extends BaseApi
{
    //单文件上传
    public function logo(){
        $data=input();
        if(empty($data['type'])){
            $data['type']='goods';
        }
        if(!in_array($data['type'],['brand','goods','category'])){
            $data['type']='goods';
        }
        $logo=request()->file('logo');
        if (empty($logo)){
            $this->error('请上传文件');
            $this->fault('请上传文件');
        }
        $path=ROOT_PATH.DS.'public'.DS.'upload'.DS.$data['type'];
        if(!is_dir($path)) mkdir($path);
        $ret=$logo->validate(['size'=>1024*1024*10,'exit'=>'jpg,png,jpeg,gif'])->move($path);
        if($ret){
            $path=DS.'upload'.$data['type'].$ret->getSaveName();
            $this->yes($path);
        }
    }
    //多文件上传
    public function images(){
        $data=input();
        if(empty($data['type'])){
            $data['type']='goods';
        }
        if(!in_array($data['type'],['goods','brand','category'])){
            $data['type']='goods';
        }
        $images=request()->file('images');
        if(empty($images)){
            $this->error('请上传文件');
            $this->fault('上传文件为空');
        }
        $path=ROOT_PATH.DS.'public'.DS.'upload'.DS.$data['type'];
        if(!is_dir($path)) mkdir($path);
        $res=[
          'success'=>[],
          'error'=>[]
        ];
//        dump($images);
        foreach ($images as $image){
            $ret=$image->validate(['size'=>1024*1024*10,'exit'=>'gif,jpg,png,jpeg'])->move($path);
            if($ret){
                $p=DS.'upload'.DS.$data['type'].DS.$ret->getSaveName();
                $res['success'][]=$p;
            }else{
                $res['error'][]=[
                    'name'=>$image->getInfo('name'),
                    'msg'=>$image->getError()
                ];
            }
        }
        $this->yes($res);
    }
}
