<?php
namespace app\user\controller;
use think\Controller;
use think\Db;
use think\Input;

class Network extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        $did = session('sid');
        $where['did'] = ['=',$did];
        $data = $this->getModel(1,'network',$where);
        $this->assign('data',$data);
        $this->assign('locat',json_encode($data['province'].','.$data['city'].','.$data['area'].','.$data['addr']));
        return $this->fetch();
    }

}
