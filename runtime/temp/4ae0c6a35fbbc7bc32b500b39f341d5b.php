<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:52:"F:\wamp\www\yajie/app/admin\view\articles\index.html";i:1507509539;s:49:"F:\wamp\www\yajie/app/admin\view\common\head.html";i:1507509539;s:49:"F:\wamp\www\yajie/app/admin\view\common\foot.html";i:1507509539;}*/ ?>
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
<div class="admin-main layui-anim layui-anim-upbit">
    <fieldset class="layui-elem-field layui-field-title">
        <legend id="mtitle"><?php echo $title; ?></legend>
    </fieldset>
    <div class="left cloumdTree">

    </div>
    <iframe src="<?php echo $fhref; ?>" frameborder="0" name="contentList" class="right contentList"></iframe>
</div>
<script type="text/javascript" src="__STATIC__/plugins/layui/layui.js"></script>


<script>
    layui.use(['tree'], function() {
        $ = layui.jquery;
        var h=$(window).height();
        $('.cloumdTree').css({"height":h+'px'});
        $('.contentList').css({"height":h+'px'});
        layui.tree({
            elem: '.cloumdTree' //传入元素选择器
            ,target:"contentList"
            ,click: function(node){
                $('#mtitle').html(node.name); //node即为当前点击的节点数据
            }
            ,nodes:[{
                name: '所有内容'
                ,spread:true
                ,id: 0
                ,href:"<?php echo url("","",true,false);?>"
                ,alias: 'changyong'
                ,children:  <?php echo $TreeShape; ?>
            }]
        });

    })
</script>