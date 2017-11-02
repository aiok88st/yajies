<?php
namespace app\user\model;
use think\Model;
class Distributor extends Model
{
    public function login($data){
        $user=db('distributor')->where('username',$data['username'])->where('is_open',1)->find();
        if($user){
            if($user['pwd'] == md5($data['password'])){
                session('uname',$user['username']);
                session('pid',$user['pid']);
                session('sid',$user['id']);
                return 1; //信息正确
            }else{
                return -1; //密码错误
            }
        }else{
            return -1; //用户不存在
        }
    }

    //修改密码
    public function change($data){
        $user['pwd'] = md5($data['password']);
        $re=db('distributor')->where('id',$data['id'])->update($user);
        if($re){
            return 1;//修改成功
        }else{
            return -1;//修改失败
        }
    }



}
