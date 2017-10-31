<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:52:"F:\wamp\www\yajie/app/admin\view\articles\lists.html";i:1507509539;s:49:"F:\wamp\www\yajie/app/admin\view\common\head.html";i:1507509539;s:49:"F:\wamp\www\yajie/app/admin\view\common\foot.html";i:1507509539;}*/ ?>
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
        <legend><?php echo $mTitle; ?>管理</legend>
    </fieldset>
    <div class="demoTable">
        <div class="layui-inline">
            <input class="layui-input" name="key" id="key" placeholder="<?php echo lang('pleaseEnter'); ?>关键字">
        </div>
        <button class="layui-btn" id="search" data-type="reload"><?php echo lang('search'); ?></button>
        <a href="<?php echo url('lists',['catid'=>$catid]); ?>" class="layui-btn">显示全部</a>
        <button type="button" class="layui-btn layui-btn-danger" id="delAll">批量删除</button>
        <a href="<?php echo url('article/add',array('catid'=>$catid)); ?>" class="layui-btn" style="float:right;"><i class="fa fa-plus" aria-hidden="true"></i><?php echo lang('add'); ?>内容</a>
        <div style="clear: both;"></div>
    </div>
    <table class="layui-table" id="list" lay-filter="list"></table>
</div>
<script type="text/javascript" src="__STATIC__/plugins/layui/layui.js"></script>


<script type="text/html" id="name">
    {{d.title}}{{# if(d.thumb){ }}<img src="__ADMIN__/images/image.gif" onmouseover="layer.tips('<img src=__PUBLIC__/{{d.thumb}}>',this,{tips: [1, '#fff']});" onmouseout="layer.closeAll();">{{# } }}
</script>
<script type="text/html" id="order">
    <input type="hidden" id="catid" name="catid" value="<?php echo $catid; ?>">
    <input name="{{d.id}}" data-id="{{d.id}}" class="list_order layui-input" value=" {{d.listorder}}" size="10"/>
</script>
<script type="text/html" id="open">
    {{# if(d.open==1){ }}
    <a class="layui-btn layui-btn-mini layui-btn-warm" lay-event="open">开启</a>
    {{# }else{  }}
    <a class="layui-btn layui-btn-mini layui-btn-danger" lay-event="open">禁用</a>
    {{# } }}
</script>
<script type="text/html" id="action">
    <a href="<?php echo url('article/edit'); ?>?id={{d.id}}&catid={{d.catid}}" class="layui-btn layui-btn-mini">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-mini" lay-event="del">删除</a>
    {{# if(d.typename != '网上商城'){ }}
        {{# if(d.status==2){ }}
        <a class="layui-btn layui-btn-danger layui-btn-mini" lay-event="addHot">取消首页推荐</a>
        {{# }else{  }}
        <a class="layui-btn layui-btn-mini" lay-event="addHot">加入首页推荐</a>
        {{# } }}
    {{# } }}
</script>
<script>
    layui.use(['table'], function() {
        var table = layui.table, $ = layui.jquery;
        var tableIn = table.render({
            id: 'ad',
            elem: '#list',
            url: '<?php echo url("Articles/lists",["catid"=>$catid]); ?>',
            where:{catid:'<?php echo input("catid"); ?>'},
            method: 'post',
            page:true,
            cols: [[
                {checkbox: true, fixed: true},
                {field: 'id', title: '<?php echo lang("id"); ?>', width: 60, fixed: true},
                {field: 'title', title: '<?php echo lang("title"); ?>', width: 180, templet: '#title'},

                {field: 'createtime', title: '<?php echo lang("add"); ?><?php echo lang("time"); ?>', width: 180},
                {field: 'listorder', align: 'center', title: '<?php echo lang("order"); ?>', width: 80, templet: '#order'},
                {field: 'open', align: 'center', title: '<?php echo lang("status"); ?>', width: 80, toolbar: '#open'},
                {width: 250, align: 'center', toolbar: '#action',title:'操作'}
            ]],
            limit:10
        });

        //搜索
        $('#search').on('click', function () {
            var key = $('#key').val();
            if ($.trim(key) === '') {
                layer.msg('<?php echo lang("pleaseEnter"); ?>关键字！', {icon: 0});
                return;
            }
            tableIn.reload({
                where: {key: key}
            });
        });
        //开启、禁用
        table.on('tool(list)', function(obj) {
            var data = obj.data;
            if (obj.event === 'open') {
                var loading = layer.load(1, {shade: [0.1, '#fff']});
                $.post('<?php echo url("editState"); ?>', {'id': data.id}, function (res) {
                    layer.close(loading);
                    if (res.status === 1) {
                        if (res.open === 1) {
                            obj.update({
                                open: '<a class="layui-btn layui-btn-mini layui-btn-warm" lay-event="open">开启</a>'
                            });
                        } else {
                            obj.update({
                                open: '<a class="layui-btn layui-btn-mini layui-btn-danger" lay-event="open">禁用</a>'
                            });
                        }
                    }else {
                        layer.msg('操作失败！', {time: 1000, icon: 2});
                        return false;
                    }
                })
            }else if(obj.event === 'del'){
                layer.confirm('您确定要删除吗？', function(index){
                    var loading = layer.load(1, {shade: [0.1, '#fff']});
                    $.post("<?php echo url('article/listDel'); ?>",{'id':data.id},function(res){
                        layer.close(loading);
                        if(res.code===1){
                            layer.msg(res.msg,{time:1000,icon:1});
                            tableIn.reload();
                        }else{
                            layer.msg('操作失败！',{time:1000,icon:2});
                        }
                    });
                    layer.close(index);
                });
            }else if(obj.event === 'addHot'){
                var loading = layer.load(1, {shade: [0.1, '#fff']});
                $.post('<?php echo url("article/addHots"); ?>', {'id': data.id}, function (res) {
                    layer.close(loading);
                    if (res.code===1) {
                        if (res.status === 1) {
                            obj.update({
                                action: '<a class="layui-btn layui-btn-mini" lay-event="addHot">加入首页推荐</a>'
                            });
                            location.reload();
                        } else {
                            obj.update({
                                action: '<a class="layui-btn layui-btn-danger layui-btn-mini" lay-event="addHot">取消首页推荐</a>'
                            });
                            location.reload();
                        }
                    }else if(res.code == 0){
                        layer.msg(res.msg, {time: 1000, icon: 2});
                    }  else {
                        layer.msg('操作失败！', {time: 1000, icon: 2});
                        return false;
                    }
                });
            }
        });


        $('body').on('blur','.list_order',function() {
            var id = $(this).attr('data-id');
            var listorder = $(this).val();
            var catid = $("#catid").val();
            var loading = layer.load(1, {shade: [0.1, '#fff']});
            $.post('<?php echo url("article/listorder"); ?>',{"id":id,"listorder":listorder,"catid":catid},function(res){
                layer.close(loading);
                if(res.code === 1){
                    layer.msg(res.msg, {time: 1000, icon: 1});
                    tableIn.reload();
                }else{
                    layer.msg(res.msg,{time:1000,icon:2});
                }
            })
        });


        $('#delAll').click(function(){
            layer.confirm('确认要删除选中的内容吗？', {icon: 3}, function(index) {
                layer.close(index);
                var checkStatus = table.checkStatus('ad'); //test即为参数id设定的值
                var ids = [];
                $(checkStatus.data).each(function (i, o) {
                    ids.push(o.id);
                });
                var loading = layer.load(1, {shade: [0.1, '#fff']});
                $.post("<?php echo url('article/delAll'); ?>", {ids: ids}, function (data) {
                    layer.close(loading);
                    if (data.code === 1) {
                        layer.msg(data.msg, {time: 1000, icon: 1});
                        tableIn.reload();
                    } else {
                        layer.msg(data.msg, {time: 1000, icon: 2});
                    }
                });
            });
        })

    })
</script>