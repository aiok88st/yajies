<?php
namespace app\admin\controller;
use think\Db;
use clt\Leftnav;
use app\admin\model\Distributor as DistributorModel;
use think\Validate;
class Distributor extends Common
{
    public function getList($key,$where){
        $map = '';
        if ($key) {
            $map['username|tel'] = array('like', "%" . $key . "%");
        }
        if (session('aid') != 1) {
            $map['admin_id'] = session('aid');
        }
        $list = Db::table(config('database.prefix') .'distributor')
            ->where($map)
            ->where($where)
            ->select();
        foreach ($list as $k=>$v ){
            $list[$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
        }
        return $list;
    }

    //经销商列表
    public function lists() {
        if (request()->isPost()) {
            $key = input('key');
            $where['pid'] = ['=',0];
            $list = $this->getList($key,$where);
            return $result = ['code' => 0, 'msg' => '获取成功!', 'data' => $list, 'rel' => 1];
        }
        return $this->fetch('list');
    }
    //分销商列表
    public function list2(){
        if (request()->isPost()) {
            $key = input('key');
            $where['pid'] = ['>',0];
            $list = $this->getList($key,$where);
            foreach($list as $k=>$v){
                $list[$k]['pname'] = db('distributor')->where('id',$v['pid'])->field('username')->find();
            }
            return $result = ['code' => 0, 'msg' => '获取成功!', 'data' => $list, 'rel' => 1];
        }
        return $this->fetch();
    }

    public function add()
    {
        if (request()->isPost()) {
            $data = input('post.');
            if($data['pid'] != 0){
                $url = url('list2');
            }else{
                $url = url('lists');
            }
            $check_user = DistributorModel::get(['username' => $data['username']]);
            if ($check_user) {
                return $result = ['code' => 0, 'msg' => '用户已存在，请重新输入用户名!'];
            }
            $data['pwd'] = input('post.pwd', '', 'md5');
            $data['add_time'] = time();
            $data['ip'] = request()->ip();
            //验证
            $msg = $this->validate($data, 'Distributor');
            if ($msg != 'true') {
                return $result = ['code' => 0, 'msg' => $msg];
            }
            //单独验证密码
            $checkPwd = Validate::is(input('post.pwd'), 'require');
            if (false === $checkPwd) {
                return $result = ['code' => 0, 'msg' => '密码不能为空！'];
            }
            //添加
            if (DistributorModel::create($data)) {
                return ['code' => 1, 'msg' => '添加成功!', 'url' => $url];
            } else {
                return ['code' => 0, 'msg' => '添加失败!'];
            }
        } else {
            $p = db('distributor')->where('pid',0)->select();
            $this->assign('p',$p);
            $this->assign('title', lang('add') . lang('经销商'));
            return $this->fetch('add');
        }
    }

    //删除管理员
    public function adminDel()
    {
        $id = input('post.id');
        if (session('aid') == 1) {
            DistributorModel::destroy(['id' => $id]);
            return $result = ['code' => 1, 'msg' => '删除成功!'];
        } else {
            return $result = ['code' => 0, 'msg' => '您没有删除管理员的权限!'];
        }
    }

    //修改管理员状态
    public function adminState()
    {
        $id = input('post.id');
        if (empty($id)) {
            $result['status'] = 0;
            $result['info'] = '用户ID不存在!';
            $result['url'] = url('lists');
            exit;
        }
        $status = db('distributor')->where('id=' . $id)->value('is_open');//判断当前状态情况
        if ($status == 1) {
            $data['is_open'] = 0;
            db('distributor')->where('id=' . $id)->update($data);
            $result['status'] = 1;
            $result['open'] = 0;
        } else {
            $data['is_open'] = 1;
            db('distributor')->where('id=' . $id)->update($data);
            $result['status'] = 1;
            $result['open'] = 1;
        }
        return $result;
    }

    //更新管理员信息
    public function edit()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $pwd = input('post.pwd');
            $map['id'] = array('neq', input('post.id'));
            $where['id'] = input('post.id');
            if($data['pid'] != 0){
                $url = url('list2');
            }else{
                $url = url('lists');
            }
            if (input('post.username')) {
                $map['username'] = input('post.username');
                $check_user = DistributorModel::get($map);
                if ($check_user) {
                    return $result = ['code' => 0, 'msg' => '用户已存在，请重新输入用户名!'];
                }
            }
            if ($pwd) {
                $data['pwd'] = input('post.pwd', '', 'md5');
            } else {
                unset($data['pwd']);
            }
            $msg = $this->validate($data, 'distributor');
            if ($msg != 'true') {
                return $result = ['code' => 0, 'msg' => $msg];
            }
            DistributorModel::update($data);
            return $result = ['code' => 1, 'msg' => '修改成功!', 'url' => $url];
        } else {
            $p = db('distributor')->where('pid',0)->select();
            $this->assign('p',$p);
//            $this->assign('p',json_encode($p,true));
            $info = DistributorModel::get(['id' => input('id')]);
            $this->assign('info', $info->toJson());
            $this->assign('title', lang('edit') . lang('经销商'));
            return view('edit');
        }
    }
    //批量删除
    public function delAll(){
        $map['id'] =array('in',input('param.ids/a'));
        $model = db('distributor');
        $model->where($map)->delete();
        $result['code'] = 1;
        $result['msg'] = '删除成功！';
        $result['url'] = url('lists');
        return $result;
    }


}