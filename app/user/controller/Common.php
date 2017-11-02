<?php
namespace app\user\controller;
use think\Request;
use think\Db;
use think\Controller;
class Common extends Controller
{

    public function _initialize()
    {
        //判断管理员是否登录
        if (!session('sid')) {
            $this->redirect('user/login/index');
        }
        define('MODULE_NAME',strtolower(request()->controller()));
        define('ACTION_NAME',strtolower(request()->action()));

    }
    //空操作
    public function _empty(){
        return $this->error('空操作，返回上次访问页面中...');
    }

    //查询
    public function getModel($type=1,$table,$where){
        if($type == 1){
            $data = db($table)->where($where)->find();
        }else{
            $data = db($table)->where($where)->select();
        }
        return $data;
    }
}
