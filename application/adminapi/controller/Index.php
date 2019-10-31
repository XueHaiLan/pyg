<?php
namespace app\adminapi\controller;

use think\Db;
use app\adminapi\controller\BaseApi;
use tools\jwt\Token;

class Index extends BaseApi
{
    public function index()
    {
        $id=$this->request->param('user_id');
        dump($id);
    }
}
