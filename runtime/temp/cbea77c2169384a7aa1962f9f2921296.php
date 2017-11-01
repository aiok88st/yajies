<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:53:"F:\wamp\www\yajie/app/admin\view\distributor\add.html";i:1509527529;s:49:"F:\wamp\www\yajie/app/admin\view\common\head.html";i:1509507433;s:49:"F:\wamp\www\yajie/app/admin\view\common\foot.html";i:1507509539;}*/ ?>
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
    <link rel="stylesheet" href="__STATIC__/plugins/layui/css/layui.css?v=1" media="all" />
    <link rel="stylesheet" href="__ADMIN__/css/global.css?v=1" media="all">
    <link rel="stylesheet" href="__STATIC__/common/css/font.css" media="all">
</head>
<body class="skin-0">
<div class="admin-main layui-anim layui-anim-upbit" ng-app="hd" ng-controller="ctrl">
    <fieldset class="layui-elem-field layui-field-title">
        <legend><?php echo $title; ?></legend>
    </fieldset>
    <form class="layui-form layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">父级经销商</label>
            <div class="layui-input-4">
                <select name="pid" lay-verify="required" ng-model="selected" ng-options="">
                    <?php if(input('t') == 1): ?>
                    <option value="0">作为父级经销商</option>
                    <?php else: if(is_array($p) || $p instanceof \think\Collection || $p instanceof \think\Paginator): $i = 0; $__LIST__ = $p;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $vo['id']; ?>"><?php echo $vo['username']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label"><?php echo lang('username'); ?></label>
            <div class="layui-input-4">
                <input type="text" name="username" ng-model="field.username" lay-verify="required" placeholder="<?php echo lang('pleaseEnter'); ?>登录用户名" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                用户名必须是以字母开头，数字、符号组合。
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php echo lang('pwd'); ?></label>
            <div class="layui-input-4">
                <input type="password" name="pwd" placeholder="<?php echo lang('pleaseEnter'); ?>登录密码" <?php if(ACTION_NAME == 'add'): ?>lay-verify="required"<?php endif; ?> class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                密码必须大于等于6位，小于15位.
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label"><?php echo lang('tel'); ?></label>
            <div class="layui-input-4">
                <input type="text" name="tel" ng-model="field.tel" lay-verify="phone" value="" placeholder="<?php echo lang('pleaseEnter'); ?>手机号" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="button" class="layui-btn" lay-submit="" lay-filter="submit"><?php echo lang('submit'); ?></button>
                <a href="<?php echo url('lists'); ?>" class="layui-btn layui-btn-primary"><?php echo lang('back'); ?></a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="__STATIC__/plugins/layui/layui.js"></script>


<script src="__STATIC__/common/js/angular.min.js"></script>
<script>
    layui.use(['form', 'layer'], function () {
        var form = layui.form, layer = layui.layer,$= layui.jquery;
        form.render();
        form.on('submit(submit)', function (data) {
            loading =layer.load(1, {shade: [0.1,'#fff']});
            $.post("<?php echo url('Distributor/add'); ?>", data.field, function (res) {
                layer.close(loading);
                if (res.code > 0) {
                    layer.msg(res.msg, {time: 1800, icon: 1}, function () {
                        location.href = res.url;
                    });
                } else {
                    layer.msg(res.msg, {time: 1800, icon: 2});
                }
            });
        })
    });

</script>