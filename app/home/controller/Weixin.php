<?php
namespace app\home\controller;
use app\home\controller\Fater;
class Weixin extends Fater{
    public function index() {
        $locaithon=$_SERVER["HTTP_REFERER"];
        session('locaithon',$locaithon);
        if (UID != 0) {
            $this->redirect('home/index/index');
        }
        $redirect_uri = urlencode('http://toupiao.snimay.com/index.php/index/Weixin/oauth');
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=state#wechat_redirect';
        header('location:'.$url);
    }
    private function access_token() {
        $state = $_GET['state'];
        $code = $_GET['code'];
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$code.'&grant_type=authorization_code';
        $token = json_decode(get_curl_contents($url), true);
        if (isset($token['errcode'])) {
            echo '<h1>错误：</h1>'.$token['errcode'];
            echo '<br/><h2>错误信息：</h2>'.$token['errmsg'];
            exit;
        }
        return $token;
    }
    //刷新access_token（如果需要）refresh_token拥有较长的有效期（7天、30天、60天、90天）
    private function refresh_token($token) {
        $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$this->appid.'&grant_type=refresh_token&refresh_token='.$token['refresh_token'];
        $rtoken = json_decode(get_curl_contents($url), true);
        if (isset($rtoken['errcode'])) {
            echo '<h1>错误：</h1>'.$rtoken['errcode'];
            echo '<br/><h2>错误信息：</h2>'.$rtoken['errmsg'];
            exit;
        }
        return $rtoken;
    }
    //拉取用户信息(需scope为 snsapi_userinfo)
    private function snsapi_userinfo($token) {
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$token['access_token'].'&openid='.$token['openid'].'&lang=zh_CN';
        $user_info =json_decode(get_curl_contents($url));
        return $user_info;
    }
    public function oauth() {
        if (UID != 0) {
            $this->redirect('home/index/index');
        }
        $token = $this->access_token();
        $user_info = (array)$this->snsapi_userinfo($token);
        if(!$user_info['openid']){
            $this->redirect('Weixin/index');
        }
        //检查是否已经存在
        $member_open = db('member_open');
        $res = $member_open->where(array('open_id' => $user_info['openid']))->find();
        //如果已经存在
        if ($res) {
            $data = array(
                'open_name' =>$this->filterEmoji($user_info['nickname']),
                'open_face' => $user_info['headimgurl'],
                'ip'=>get_client_ip()
            );
            //保存用户信息
            $member_open->where(array('open_id' => $user_info['openid']))->update($data);
            //如果已经存在，则判断是否已经绑定，有绑定则直接登陆，没有绑定则跳转进行绑定
            $state =  1;
            $open_info =[
                'open_id'=>$user_info['openid'],
                'open_name'=>$this->filterEmoji($user_info['nickname']),
                'open_face'=>$user_info['headimgurl'],
            ];
            session('member_id', $res['id']);
        }else {
            //如果不存在,则记录进行跳转绑定已经会员或注册新会员
            $data = array(
                'open_id' => $user_info['openid'],
                'open_name' =>$this->filterEmoji($user_info['nickname']),
                'open_face' => $user_info['headimgurl'],
                'addtime'=>time(),
                'ip'=>get_client_ip()
            );
            $member_open->insert($data);
            $member_id=$member_open->getLastInsID();
            $open_info = array(
                'open_id' => $user_info['openid'],
                'open_name' =>$this->filterEmoji($user_info['nickname']),
                'open_face' => $user_info['headimgurl']
            );
            session('member_id', $member_id);
        }
        session('open',$open_info);
        $this->redirect('home/index/index');
    }
    public function filterEmoji($str)
    {
        $str = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);
        return $str;
    }
}
