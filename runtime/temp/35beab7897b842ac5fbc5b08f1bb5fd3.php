<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:54:"F:\wamp\www\yajie/app/home\view\index\webShopList.html";i:1509616419;s:50:"F:\wamp\www\yajie/app/home\view\common\header.html";i:1509612289;s:50:"F:\wamp\www\yajie/app/home\view\common\footer.html";i:1509605670;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <title>售后网点</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable"/>
    <meta content="black" name="apple-mobile-web-app-status-bar-style"/>
    <meta content="telephone=no" name="format-detection"/>
    <link rel="stylesheet" href="__HOME__/css/reset.css" />
    <link rel="stylesheet" href="__HOME__/css/public.css" />
    <link rel="stylesheet" href="__HOME__/css/webShopList.css" />
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="__HOME__/js/rem.js" ></script>
    
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
    $.get("<?php echo url('home/index/jssdk'); ?>",function(res){
        wx.config({
            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId:res.appId, // 必填，公众号的唯一标识
            timestamp: res.timestamp, // 必填，生成签名的时间戳
            nonceStr:res.nonceStr, // 必填，生成签名的随机串
            signature:res.signature,// 必填，签名，见附录1
            jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage','openLocation'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });
    },"JSON")
</script>

</head>


<div id="shopList">
    <!--导航栏-->
    <div id="head" class="rHead">
        <div class="hleft return floatl">
            <a href="###">&nbsp;&nbsp;&nbsp;&nbsp;</a>
        </div>
        <div class="hmiddle floatl">
            <p>附近网点</p>
        </div>
        <div class="hright floatl">

        </div>
        <div class="clearl"></div>
    </div>
    <div class="shopList" style="display: none;">
        <div class="addressBox">
            <div class="addressShow">
                <ul>
                    <li>
                        <p>1.佛山国美雅洁维修有限公司</p>
                    </li>
                    <li>
                        <p>佛山新一城广场五楼售后部</p>
                    </li>
                </ul>
            </div>
            <div class="addressMethod">
                <div class="go floatl">
                    <a href="###">到这去</a>
                </div>
                <div class="phone floatl">
                    <a href="###">打电话</a>
                </div>
                <div class="clearl"></div>
            </div>
        </div>
        <div class="addressBox">
            <div class="addressShow">
                <ul>
                    <li>
                        <p>1.佛山国美雅洁维修有限公司</p>
                    </li>
                    <li>
                        <p>佛山新一城广场五楼售后部</p>
                    </li>
                </ul>
            </div>
            <div class="addressMethod">
                <div class="go floatl">
                    <a href="###">到这去</a>
                </div>
                <div class="phone floatl">
                    <a href="###">打电话</a>
                </div>
                <div class="clearl"></div>
            </div>
        </div>
        <div class="addressBox">
            <div class="addressShow">
                <ul>
                    <li>
                        <p>1.佛山国美雅洁维修有限公司</p>
                    </li>
                    <li>
                        <p>佛山新一城广场五楼售后部</p>
                    </li>
                </ul>
            </div>
            <div class="addressMethod">
                <div class="go floatl">
                    <a href="###">到这去</a>
                </div>
                <div class="phone floatl">
                    <a href="###">打电话</a>
                </div>
                <div class="clearl"></div>
            </div>
        </div>
        <div class="addressBox">
            <div class="addressShow">
                <ul>
                    <li>
                        <p>1.佛山国美雅洁维修有限公司</p>
                    </li>
                    <li>
                        <p>佛山新一城广场五楼售后部</p>
                    </li>
                </ul>
            </div>
            <div class="addressMethod">
                <div class="go floatl">
                    <a href="###">到这去</a>
                </div>
                <div class="phone floatl">
                    <a href="###">打电话</a>
                </div>
                <div class="clearl"></div>
            </div>
        </div>
    </div>
    <div class="shopSearch">
        <!--<div class="searchBox" style="display: none;">-->
            <!--<p>广东省  广州市  海珠区</p>-->
        <!--</div>-->
        <div class="searchCondition">
            <ul>
                <li class="choiceArea">
                    <p>所在省份</p>
                    <select name="province"  onchange="loadRegion('province',2,'city','<?php echo url('getAddrs'); ?>')" id="province">
                        <?php if(is_array($province) || $province instanceof \think\Collection || $province instanceof \think\Paginator): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $vo['id']; ?>" ><?php echo $vo['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <img src="__HOME__/img/circle.png" class="selected circle"/>
                    <img src="__HOME__/img/right.png" />
                </li>
                <li class="choiceArea">
                    <p>所在城市</p>
                    <select name="city" onchange="loadRegion('city',3,'area','<?php echo url('getAddrs'); ?>')" id="city">
                    </select>
                    <img src="__HOME__/img/circle.png" class="circle" />
                    <img src="__HOME__/img/right.png" />
                </li>
                <li class="choiceArea">
                    <p>所在县区</p>
                    <select name="area" id="area">
                    </select>
                    <img src="__HOME__/img/circle.png" class="circle" />
                    <img src="__HOME__/img/right.png" />
                </li>
            </ul>
        </div>
        <div class="searchBtn">
            <button>查找</button>
        </div>
    </div>
</div>



</body>
</html>

<script type="text/javascript" src="__HOME__/js/webShopList.js" ></script>
