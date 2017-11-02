<?php
namespace app\user\controller;
use think\Controller;
use think\Db;
use think\Input;
use app\user\model\Distributor;
class Index extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        return $this->fetch();
    }

    //退出登陆
    public function logout(){
        session(null);
        $this->redirect('user/login/index');
    }

    //修改密码
    public function changPwd(){
        if(request()->isPost()) {
            $admin = new Distributor();
            $data = input('post.');
            $result = $this->validate($data, [
                    'password|密码'  => ['require','max:15','min:6','confirm'],
                ]);
            if(true !== $result){
                // 验证失败 输出错误信息
                return json(['code'=>0, 'msg'=>$result,]);
            }
            $user = [
                'id'=>session('sid'),
                'password'=>$data['password']
            ];
            $num = $admin->change($user);
            if($num == 1){
                return json(['code' => 1, 'msg' => '修改成功!', 'url' => url('user/index/index')]);
            }else {
                return json(array('code' => 0, 'msg' => '修改失败!'));
            }
        }
        return $this->fetch('pwd');
    }
    
}
