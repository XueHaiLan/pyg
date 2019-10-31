<?php

namespace app\home\controller;

use app\common\model\Category;
use think\Collection;
use think\Controller;
use think\Request;

class Base extends Controller
{
    //
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->getCategory();
    }
    //获取category表中的数据
    public function getCategory(){
        //先从缓存中去
        $category=cache('category');
        if(empty($category)){
            //缓存中没有再从数据表中取
            $category=Category::select();
            $category=(new Collection($category))->toArray();
            $category=get_tree_list($category);
            cache('category',$category,86400);
        }
        $this->assign('category',$category);
    }
}
