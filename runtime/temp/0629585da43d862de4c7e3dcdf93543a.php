<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:50:"F:\wamp\www\yajie/app/user\view\network\index.html";i:1509590467;}*/ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="__STATIC__/plugins/layui/css/layui.css?v=1" media="all" />
    <script type="text/javascript" src="__STATIC__/plugins/layui/layui.js"></script>
    <title>我的门店</title>
</head>
<body>
    <div>
        <table class="layui-table">
            <colgroup>
                <col width="150">
                <col width="200">
                <col>
            </colgroup>
            <thead>
            <tr>
                <th>门店名</th>
                <th>联系电话</th>
                <th>门店地址</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo $data['title']; ?></td>
                <td><?php echo $data['tel']; ?></td>
                <td><?php echo $data['province']; ?><?php echo $data['city']; ?><?php echo $data['area']; ?><?php echo $data['addr']; ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="container" style="width:90%; height:600px;margin: auto;"></div>
</body>
</html>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<script>
    var locat = <?php echo $locat; ?>;
    var geocoder,map,marker = null;
    var init = function() {
        var center = new qq.maps.LatLng(39.916527,116.397128);
        map = new qq.maps.Map(document.getElementById('container'),{
            center: center,
            zoom: 15
        });
        //调用地址解析类
        geocoder = new qq.maps.Geocoder({
            complete : function(result){
                map.setCenter(result.detail.location);
                var marker = new qq.maps.Marker({
                    map:map,
                    position: result.detail.location
                });
            }
        });
        codeAddress();
    }

    function codeAddress() {
        var address = "中国,"+locat;
        //通过getLocation();方法获取位置信息值
        geocoder.getLocation(address);
    }

    init();
</script>