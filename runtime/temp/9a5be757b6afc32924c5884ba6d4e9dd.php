<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:46:"F:\wamp\www\yajie/app/user\view\index\pwd.html";i:1509593041;}*/ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="__STATIC__/plugins/layui/css/layui.css?v=1" media="all" />
    <script type="text/javascript" src="__STATIC__/plugins/layui/layui.js"></script>
    <title>修改密码</title>
</head>
<body>
<form class="layui-form" action="">

    <div class="layui-form-item">
        <label class="layui-form-label">新密码</label>
        <div class="layui-input-inline">
            <input type="password" name="password" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">密码必须大于6位，小于15位.</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">确认密码</label>
        <div class="layui-input-inline">
            <input type="password" name="password_confirm" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">密码必须大于6位，小于15位.</div>
    </div>


    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="submit">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
</body>
</html>
<script>
    layui.use('form',function(){
        var form = layui.form,$ = layui.jquery;
        //监听提交
        form.on('submit(submit)', function(data){
            loading =layer.load(1, {shade: [0.1,'#fff'] });//0.1透明度的白色背景
            $.post('<?php echo url("user/index/changPwd"); ?>',data.field,function(res){
                layer.close(loading);
                if(res.code == 1){
                    layer.msg(res.msg, {icon: 1, time: 1000}, function(){
                        location.href = res.url;
                    });
                }else{
                    layer.msg(res.msg, {icon: 2, anim: 6, time: 1000});
                }
            });
            return false;
        });
    });

</script>