@include('public/header')
@include('public/left')

<div style="margin-top: 40px"></div>
<div style="margin-left: 200px">
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">权限名称</label>
        <div class="layui-input-inline">
            <input type="hidden" name="power_id" value="{{$power->power_id}}">
            <input type="text" name="power_name" value="{{$power->power_name}}" required  lay-verify="required" placeholder="请输入权限名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">权限路径</label>
        <div class="layui-input-inline">
            <input type="text" name="power_url" value="{{$power->power_url}}" required  lay-verify="required" placeholder="请输入权限路径" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">层级</label>
        <div class="layui-input-inline">
            <input type="text"  value="{{$power->power_level}}" disabled="disabled" style="color: #0000ee" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="form">修改</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
</div>

<script>
    var $
    layui.use('form', function(){
        var form = layui.form;
        $ = layui.jquery
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        form.on('submit(form)',function(data){
            layer.msg(JSON.stringify(data.field));

            $.ajax({
                url:'{{url('admin/power/upd')}}',
                data:data.field,
                type:'post',
                dataType:'json',
                success:function( json_info ){
                    if( json_info.status == 200 ){
                        layer.open({
                            title: '权限'
                            ,content: '修改'
                        });
                        location.href="/admin/power/index"
                    }else{
                        layer.open({
                            title: '权限'
                            ,content: '添加失败'
                        });
                    }
                }
            })
            return false
        })
    })

</script>
@include('public/footer')