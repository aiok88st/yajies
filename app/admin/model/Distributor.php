<?php
namespace app\admin\model;
use think\Model;
class Distributor extends Model
{
    public function login($data){
        $user=db('distributor')->where('username',$data['username'])->where('is_open',1)->find();
        if($user){
            if($user['pwd'] == md5($data['password'])){
                session('users',$user);
                session('sid',$user['id']);
                return 1; //信息正确
            }else{
                return -1; //密码错误
            }
        }else{
            return -1; //用户不存在
        }
    }



}
