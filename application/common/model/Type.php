<?php

namespace app\common\model;

use think\Model;

class Type extends Model
{
    protected $hidden=['create_time','update_time','delete_time'];
    //类型---规格表关联
    public function specs(){
        return $this->hasMany('Spec','type_id','id');
    }
    //类型---属性表关联
    public function attrs(){
        return $this->hasMany('Attribute','type_id','id');
    }
}
