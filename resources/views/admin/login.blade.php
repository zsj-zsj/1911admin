
<!doctype html>
<html  class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>管理员登录</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="/layui/login/css/font.css">
    <link rel="stylesheet" href="/layui/login/css/xadmin.css">
    <script type="text/javascript" src="/layui/login/jq.js"></script>
    <script src="/layui/login/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/layui/login/js/xadmin.js"></script>
    <script type="text/javascript" src="/layui/login/js/cookie.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body class="login-bg">

<div class="login layui-anim layui-anim-up">
    <div class="message">管理员登录</div>
    <div id="darkbannerwrap"></div>

    <form method="post" class="layui-form" >
        <input name="admin_name" placeholder="用户名"  type="text"  class="layui-input" >
        <hr class="hr15">
        <input name="admin_pwd"  placeholder="密码"  type="password" class="layui-input">
        <hr class="hr15">
        <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="button">
        <hr class="hr20" >
    </form>
</div>

<script>
    var $
    layui.use('form', function(){
        var form = layui.form
        $ = layui.jquery
        var layer = layui.layer;
        form.on('submit(login)', function(data){
//            layer.msg(JSON.stringify(data.field));
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            if( data.field.admin_name == '' ){
                layer.msg('请输入用户名');
                return false;
            }
            if( data.field.admin_pwd == '' ){
                layer.msg('请输入密码');
                return false;
            }
            $.ajax({
                url:'{{url('/login')}}',
                data:data.field,
                type:'post',
                dataType:'json',
                success:function( res ){
                    if( res.status == 200 ){
                        layer.open({
                            title: '登录'
                            ,content: '登陆成功'
                        });
                        location.href='/'
                    }else{
                        layer.open({
                            title: '登录'
                            ,content: res.msg
                        });
                    }
                }
            })
        });
    })
</script>

</body>
</html>