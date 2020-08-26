@include('public/header')
@include('public/left')

<div style="margin-top: 40px"></div>
<div style="margin-left: 200px">
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">管理员名称</label>
        <div class="layui-input-inline">
            <input type="hidden" value="{{$data->admin_id}}" name="admin_id">
            <input type="text" name="admin_name" value="{{$data->admin_name}}"  placeholder="请输入管理员名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">邮箱</label>
        <div class="layui-input-inline">
            <input type="text" name="admin_email" value="{{$data->admin_email}}" required  placeholder="请输入管理员邮箱" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">手机号</label>
        <div class="layui-input-inline">
            <input type="text" name="admin_mobile" value="{{$data->admin_mobile}}" required   placeholder="请输入手机号" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">用户状态</label>
        <div class="layui-input-inline">
            <input type="radio" name="admin_status" value="1" title="待审核" {{$data->admin_status==1 ? 'checked' :''}}>
            <input type="radio" name="admin_status" value="2" title="锁定或删除" {{$data->admin_status==2 ? 'checked' :''}} >
            <input type="radio" name="admin_status" value="3" title="正常" {{$data->admin_status==3 ? 'checked' :''}} >
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

        form.on('submit(form)', function(data){
            layer.msg(JSON.stringify(data.field));
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url:'{{url('admin/admin/upd')}}',
                data:data.field,
                type:'post',
                dataType:'json',
                success:function( res ){
                    if( res.status == 200 ){
                        layer.open({
                            title: '管理员'
                            ,content: '修改成功'
                        });
                        location.href="/admin/admin/index"
                    }else{
                        layer.open({
                            title: '管理员'
                            ,content: '无修改内容'
                        });
                        location.href="/admin/admin/index"
                    }
                }
            })
        });

    })

</script>
@include('public/footer')