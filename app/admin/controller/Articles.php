<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Controller;
class Articles extends Common
{
    public function _initialize()
    {
        parent::_initialize();
    }
    //获取导航分类
    public function index(){

        return $this->fetch();
    }

}
