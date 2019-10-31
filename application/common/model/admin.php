<?php

namespace app\common\model;

use think\Model;

class admin extends Model
{
    //
    public function setPasswordAttr($values){
        return enctypt_password($values);
    }

}
