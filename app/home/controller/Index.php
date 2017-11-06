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
        $this->assign('area',json_encode([1]));
        $this->assign('province',$province);
        return $this->fetch('webShop');
    }
    public function getRegion(Request $request){
        $param=$request->param();
        $p=str_replace('省','',$param['province']);
        $c=str_replace('市','',$param['city']);
        $pid=db('region')->where('name',$p)->value('id');
        $cpid=db('region')->where('name',$c)->where('pid',$pid)->value('id');
        $region=db('region')->where('name',$param['district'])->where('pid',$cpid)->value('id');

        $url=url('index/webShop')."?region=".$region."&city=".$c."&lats=".$param['lats']."&longs=".$param['longs'];
        $this->redirect($url);
    }
    //获取地理位置
    public function getJs(){
        $wxapi=new \org\Wxapi;
        $url=$_SERVER['HTTP_REFERER'];
        $data = $wxapi->getSignPackage($url);
        return $data;
    }

    //省市区三级联动
    public function getAddrs(){
        $list = getLocation('region',input('pid'),input('type'));
        return json($list);
    }

    //售后网点
    public function webShop(){
        $city = input('city');
        $lats = input('lats');
        $longs = input('longs');
        $regionId = input('region');

        $region=db('region')->where('id',$regionId)->field('type,pid')->find();
        $net = db('network');
        $order = "listorder asc";
        switch($region['type']){
            case 3:
                $map['area']=['=',$regionId];
                $area =$this->getNetwork($map,$order);
                break;
            case 2:
                $map['city']=['=',$regionId];
                $area =$this->getNetwork($map,$order);
                break;
            case 1:
                $map['province']=['=',$regionId];
                $area =$this->getNetwork($map,$order);
                break;
            case 0:
                $area = '';
                break;
        }
        foreach($area as $k=>$v){
            $latlng=explode(',',$v['location']);
            $area[$k]['distance'] = getDistance($longs,$lats,$latlng[1],$latlng[0],2);
        }
    
        $this->assign('pid',$region['pid']);
        $this->assign('areas',$area);
        $this->assign('area',json_encode($area));
        $this->assign('city',$city);
        $this->assign('lats',$lats);
        $this->assign('longs',$longs);
        return $this->fetch('webShop');
    }

    public function shopList(){
        $province = db('region')->where('pid',1)->select();
        $this->assign('province',$province);
        return $this->fetch('webShopList');
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
