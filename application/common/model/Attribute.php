<?php

namespace app\common\model;

use think\Model;

class Attribute extends Model
{
    //
    protected $hidden=['create_time','update_time','delete_time'];

    //对attr-values字段进行转化
    public function getAttrValuesAttr($value){
        return  $value ? explode(',',$value) : [];
    }
}
