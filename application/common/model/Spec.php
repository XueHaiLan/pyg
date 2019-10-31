<?php

namespace app\common\model;

use think\Model;

class Spec extends Model
{
    protected $hidden=['create_time','update_time','delete_time'];
    //规格名---规格值表关联
    public function specValues(){
        return $this->hasMany('SpecValue','spec_id','id');
    }
}
