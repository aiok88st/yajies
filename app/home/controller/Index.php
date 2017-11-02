<?php
namespace app\home\controller;
use think\Controller;
use app\home\controller\Fater;
use think\Request;
use think\Db;
class Index extends Fater
{

    public function _initialize(){
        parent::_initialize();
    }

    public function index(){
        $province = db('region')->where('pid',1)->select();
        $this->assign('province',$province);
        return $this->fetch('webShopList');
    }
    //获取地理位置
    public function getJs(){
        $wxapi=new \org\Wxapi;
        $url = "http://archie.hengdikeji.com/yajie/";
        $data = $wxapi->getSignPackage($url);
        return $data;
    }

    //省市区三级联动
    public function getAddrs(){
        $list = getLocation('region',input('pid'),input('type'));
        echo json_encode($list);
    }

    //判断是否登录
    public function hasAuth(Request $request){
        if(UID){
            return json([
                'code'=>1,
                'token'=>$request->token(),
                'msg'=>'登录成功'
            ]);
        }else{
            return json([
                'code'=>408,
                'msg'=>'登录失败'
            ]);
        }
    }

    //微信分享
    public function jssdk_all(){
        $wxapi=new \org\Wxapi;
        $url=$_SERVER['HTTP_REFERER'];
        $signPackage=$wxapi->getSignPackage($url);
        return json($signPackage);
    }


}
