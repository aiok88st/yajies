<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:48:"F:\wamp\www\yajie/app/user\view\index\index.html";i:1509591336;}*/ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="__STATIC__/plugins/layui/css/layui.css?v=1" media="all" />
    <script type="text/javascript" src="__STATIC__/plugins/layui/layui.js"></script>
    <title>会员中心</title>
</head>
<body>
    <div>
        <div>我的会员中心--
                <a href="javascript:;" class="admin-header-user">
                    <span><?php echo session('uname'); ?></span>
                </a>
            <a href="<?php echo url('user/index/changPwd'); ?>"><i class="fa fa-sign-out" aria-hidden="true"></i>修改密码</a>

            <a href="<?php echo url('user/index/logout'); ?>"><i class="fa fa-sign-out" aria-hidden="true"></i>退出</a>

        </div>
        <div style="margin-top: 10px;width: 100px;float: left;">
            <ul>
                <li><a href="<?php echo url('user/Network/index'); ?>" target="contentList">我的门店</a></li>
                <li><a href="<?php echo url('user/index/logout'); ?>" target="contentList">我的维修订单</a></li>
                <li><a href="<?php echo url('user/index/logout'); ?>" target="contentList">我的视频</a></li>
                <li><a href="<?php echo url('user/index/logout'); ?>" target="contentList">我的考核</a></li>
            </ul>
        </div>
        <div style="width: 80%;height: 1200px;float: right;">
            <iframe src="" frameborder="0" name="contentList" style="width: 100%;height: 100%;"></iframe>
        </div>
    </div>
</body>
</html>