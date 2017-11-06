<?php
namespace app\home\controller;
use think\Controller;
use think\Exception;
use think\Db;
class Fater extends Controller{
    protected $appid;
    protected $appsecret;
    public function _initialize() {
        //微信登录秘钥
        $this->appid="wxe51333b9199fa330"; //
        $this->appsecret="5b5334a627ccc6a461005dfb89e7aaa9"; //
        define('WX_APPID',  $this->appid);
        define('WX_APPSECRET', $this->appsecret);
        $member_id=session('member_id');
        //定义用户常量
        define('UID', 1);//用户ID
//        define('UID', $member_id);//用户ID
    }

    //base64上传
    public function upload($data){
        try{
            if(empty($data)){
                return ['code'=>0,'thumb'=>''];
            }
            $filePath = base64_decode($data);
            $toDay=date('Ymd');
            if(!file_exists("public/uploads/{$toDay}")){
                mkdir("public/uploads/{$toDay}/",0777,true);
            }
            $thumbNname=rand(999,10000) . date('YmdHis') . rand(0, 9999) . '.' . 'jpg';
            $keys = "public/uploads/{$toDay}/".$thumbNname;
            $path="/uploads/{$toDay}/".$thumbNname;
            file_put_contents($keys,$filePath);
            return ['code'=>1,'thumb'=>$path];
        }catch(Exception $e){
            return ['code'=>$e->getMessage()];
        }
    }
    //是否关注公众号
    public function getOpen(){
        $wxapi=new \org\Wxapi;
        $token=$wxapi->get_token();
        $openId=db('member_open')->where('id',UID)->value('open_id');
        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$openId."&lang=zh_CN";
        $res=get_curl_contents($url);
        $res=json_decode($res,true);
        if(isset($res['subscribe'])){
            if($res['subscribe']!=1){
                return ['code'=>0,'msg'=>'请先关注公众号"诗尼曼家居"'];
            }
        }
        return ['code'=>1];
    }

    public function getNetwork($map,$order){
        $list=Db::table(config('database.prefix').'network')->alias('a')
            ->join(config('database.prefix').'region ag','a.province = ag.id','left')
            ->join(config('database.prefix').'region r','a.city = r.id','left')
            ->join(config('database.prefix').'region rs','a.area = rs.id','left')
            ->field('a.*,ag.name as province,r.name as city,rs.name as area')
            ->where($map)
            ->order($order)
            ->select();
        return $list;
    }

}