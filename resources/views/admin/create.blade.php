@include('public/header')
@include('public/left')

<div style="margin-top: 40px"></div>
<div style="margin-left: 200px">
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">管理员名称</label>
        <div class="layui-input-inline">
            <input type="text" name="admin_name"  placeholder="请输入管理员名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">邮箱</label>
        <div class="layui-input-inline">
            <input type="text" name="admin_email" required  placeholder="请输入管理员邮箱" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">管理员密码</label>
        <div class="layui-input-inline">
            <input type="password" name="admin_pwd" required   placeholder="请输入管理员密码" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">确认密码</label>
        <div class="layui-input-inline">
            <input type="password" name="admin_pwds" required   placeholder="确认密码" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">手机号</label>
        <div class="layui-input-inline">
            <input type="text" name="admin_mobile" required   placeholder="请输入手机号" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">管理员类型</label>
        <div class="layui-input-block">
            <input type="radio" name="admin_type" lay-filter="admin_type" value="1" title="超级管理员" >
            <input type="radio" name="admin_type" lay-filter="admin_type" value="2" title="普通管理员" checked>
        </div>
    </div>

    <div class="layui-form-item" id="all_role">
        <label class="layui-form-label">角色添加</label>
        <div class="layui-input-block">
            <div class="role">
                @foreach($rolePower as $k=>$v)
                    <div class="role_name">
                        <input type="checkbox" name="role[]" lay-filter="parent"  value="{{$v['role_id']}}"
                               lay-skin="primary" title="{{$v['role_name']}}">
                    </div>
                    <br>
                    <div class="role_parent"> <b>权限：</b>
                        @foreach( $v['power_list'] as $key => $val )
                            {{$val['power_name']}} &nbsp;&nbsp;&nbsp;&nbsp;
                        @endforeach
                    </div>
                @endforeach
                    <hr>
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" type="button" lay-submit lay-filter="form">添加</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
</div>

<script>
    var $
    layui.use('form', function(){
        var form = layui.form
        $ = layui.jquery
        var layer = layui.layer;
        form.on('radio(admin_type)', function(data){
            if( data.value == 1 ) {
                layer.msg('超级管理员不受后台权限控制');
                $('#all_role').hide();
            }else{
                $('#all_role').show();
            }
        })
        form.on('submit(form)', function(data){
            layer.msg(JSON.stringify(data.field));
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url:'{{url('admin/admin/create')}}',
                data:data.field,
                type:'post',
                dataType:'json',
                success:function( res ){
                    if( res.status == 200 ){
                        layer.open({
                            title: '管理员'
                            ,content: '添加成功'
                        });
                        location.href='/admin/admin/index'
                    }else{
                        layer.open({
                            title: '管理员'
                            ,content: res.msg
                        });
                    }
                }
            })
        });

    })

</script>
@include('public/footer')