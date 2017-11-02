<?php
namespace app\user\controller;
use think\Controller;
use app\user\model\Distributor;
class Login extends Controller
{
    public function _initialize(){
        if (session('sid')) {
            $this->redirect('user/index/index');
        }
    }

    public function index(){
        if(request()->isPost()) {
            $admin = new Distributor();
            $data = input('post.');
            $num = $admin->login($data);
            if($num == 1){
                return json(['code' => 1, 'msg' => '登录成功!', 'url' => url('user/index/index')]);
            }else {
                return json(array('code' => 0, 'msg' => '用户名或者密码错误，重新输入!'));
            }
        }else{
            return $this->fetch();
        }
    }

}