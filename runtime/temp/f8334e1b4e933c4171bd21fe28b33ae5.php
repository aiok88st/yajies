<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:50:"F:\wamp\www\yajie/app/admin\view\networks\add.html";i:1509421448;s:49:"F:\wamp\www\yajie/app/admin\view\common\head.html";i:1507509539;s:49:"F:\wamp\www\yajie/app/admin\view\common\foot.html";i:1507509539;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo config('sys_name'); ?>后台管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="__STATIC__/plugins/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="__ADMIN__/css/global.css" media="all">
    <link rel="stylesheet" href="__STATIC__/common/css/font.css" media="all">
</head>
<body class="skin-0">
<link rel="stylesheet" href="__STATIC__/plugins/spectrum/spectrum.css">
<script type="text/javascript">
    function getProvince(){
        $.ajax({
            url:"<?php echo url('getAddrs'); ?>",
            type:'get',
            data:'pid=1',
            success:function(re){
                $("#province").empty();
                $("#province").append(re);
                getCity();
            }
        })
    }
    function getCity(){
        var pid=$("#province").val();
        $.ajax({
            url:"<?php echo url('getAddrs'); ?>",
            type:'get',
            data:'pid='+pid,
            success:function(re){
                $("#city").empty();
                $("#city").append(re);
                getArea();
            }
        })
    }
    function getArea(){
        var cid=$("#city").val();
        $.ajax({
            url:"<?php echo url('getAddrs'); ?>",
            type:'get',
            data:'pid='+cid,
            success:function(re){
                $("#district").empty();
                $("#district").append(re);
            }
        })
    }

</script>
<script>
    var ADMIN = '__ADMIN__';
    var UPURL = "<?php echo url('UpFiles/upImages'); ?>";
    var PUBLIC = "__PUBLIC__";
    var imgClassName,fileClassName;
</script>
<script type="text/javascript" src="__STATIC__/plugins/layui/layui.js"></script>


<script src="__STATIC__/common/js/jquery.2.1.1.min.js"></script>
<script>
    var edittext=new Array();
</script>

<script src="__STATIC__/ueditor/ueditor.config.js" type="text/javascript"></script>
<script src="__STATIC__/ueditor/ueditor.all.min.js" type="text/javascript"></script>
<div class="admin-main layui-anim layui-anim-upbit">
    <fieldset class="layui-elem-field layui-field-title">
        <legend><?php echo $title; ?></legend>
    </fieldset>
    <form class="layui-form" method="post">

        <div class="layui-form-item">
            <label class="layui-form-label">门店名</label>
            <div class="layui-input-block">
                <input type="text" name="title" required  lay-verify="required" placeholder="请输入门店名" autocomplete="off" class="layui-input" style="width: 50%;">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">联系电话</label>
            <div class="layui-input-block">
                <input type="text" name="tel" required  lay-verify="required" placeholder="请输入联系电话" autocomplete="off" class="layui-input" style="width: 50%;">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">门店地址</label>
            <div class="">
                <select name="province" onchange="getCity()" id="province" lay-ignore style="width: 6%;height: 36px;float: left;margin-right: 10px;border-color: #D2D2D2!important;">
                </select>
            </div>
            <div class="">
                <select name="city" onchange="getArea()" id="city" lay-ignore style="width: 6%;height: 36px;float: left;margin-right: 10px;border-color: #D2D2D2!important;">
                </select>
            </div>
            <div class="">
                <select name="area" id="district" lay-ignore style="width: 6%;height: 36px;float: left;margin-right: 10px;border-color: #D2D2D2!important;">
                </select>
            </div>
            <div class="layui-input-inline">
                <input type="text" name="addr" id="addr" required  lay-verify="required" placeholder="请输入详细地址" autocomplete="off" class="layui-input" >
            </div>
            <div class="layui-input-inline" style="margin-left: 10px;width: 90px">
                <button class="layui-btn" id="search" data-type="reload" onclick="codeAddress()"><?php echo lang('search'); ?></button>
            </div>

        </div>
        <script type="text/javascript">
            getProvince();
        </script>

        <div id="container" style="width:90%; height:600px;margin: auto;"></div>


        <div class="layui-form-item" style="margin-top: 30px">
            <div class="layui-input-block">
                <button type="button" class="layui-btn" lay-submit="" lay-filter="submit"><?php echo lang('submit'); ?></button>

                <a href="<?php echo url('index',['catid'=>input('catid')]); ?>" class="layui-btn layui-btn-primary"><?php echo lang('back'); ?></a>

            </div>
        </div>
        <input type="hidden" name="location" value="" id="locate">
    </form>
</div>
<script src='__STATIC__/plugins/spectrum/spectrum.js'></script>
<script src='__ADMIN__/js/edit.js'></script>

<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>

</head>

<script>
    var thumb,pic,file;
    <?php if(ACTION_NAME=='add'): ?>
    var url= "<?php echo url('insert'); ?>";
    <?php else: ?>
    var url= "<?php echo url('update'); ?>";
    <?php endif; ?>

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
                $("#locate").attr('value',result.detail.location);
                //console.log(result.detail.location);
            }
        });
    }

    function codeAddress() {
       var province = $("#province").find("option:selected").text();
       var city=$("#city").find("option:selected").text();
       var area=$("#district").find("option:selected").text();
       var addr=$("#addr").val();
        var address = "中国,"+province+","+city+","+area+","+addr;
        //通过getLocation();方法获取位置信息值
        geocoder.getLocation(address);

    }

    init();

    layui.use(['form','upload','layedit','laydate'], function () {
        var form = layui.form,upload = layui.upload,layedit = layui.layedit,laydate = layui.laydate;
        //缩略图上传
        upload.render({
            elem: '#thumbBtn'
            ,url: '<?php echo url("UpFiles/upload"); ?>'
            ,accept: 'images' //普通文件
            ,exts: 'jpg|png|gif' //只允许上传压缩文件
            ,done: function(res){
                console.log(res);
                $('#cltThumb').attr('src', "__PUBLIC__"+res.url);
                $('#thumb').val(res.url);
            }
        });
        //多图片上传
        var imagesSrc;
        upload.render({
            elem: '#test2'
            ,url: '<?php echo url("UpFiles/upImages"); ?>'
            ,multiple: true
            ,done: function(res){
                $('#demo2 .layui-row').append('<div class="layui-col-md3"><div class="dtbox"><img src="__PUBLIC__'+ res.src +'" class="layui-upload-img"><input type="hidden" class="imgVal" value="'+ res.src +'"> <i class="delimg layui-icon">&#x1006;</i></div></div>');
                imagesSrc +=res.src+';';
            }
        });
        //日期
        laydate.render({
            elem: '#addtime', //指定元素
            type:'datetime',
            format:'yyyy-MM-dd HH:mm:ss'
        });
        form.on('submit(submit)', function (data) {
            if(edittext){
                for (key in edittext){
                    data.field[key] = $(window.frames["LAY_layedit_"+edittext[key]].document).find('body').html();
                }
            }
            var images='';
            $(".imgVal").each(function(i) {
                images+=$(this).val()+';';
            });
            data.field.images = images;
            $.post(url, data.field, function (res) {
                if (res.code > 0) {
                    layer.msg(res.msg, {time: 1800, icon: 1}, function () {
                        location.href = res.url;
                    });
                } else {
                    layer.msg(res.msg, {time: 1800, icon: 2});
                }
            });
        });
        $('.layui-row').on('click','.delimg',function(){
            var thisimg = $(this);
            layer.confirm('你确定要删除该图片吗？', function(index){
                thisimg.parents('.layui-col-md3').remove();
                layer.close(index);
            })
        })
    });

</script>