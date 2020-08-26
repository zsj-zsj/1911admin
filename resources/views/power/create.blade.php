@include('public/header')
@include('public/left')

<div style="margin-top: 40px"></div>
<div style="margin-left: 200px">
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">权限名称</label>
        <div class="layui-input-inline">
            <input type="text" name="power_name" required  lay-verify="required" placeholder="请输入权限名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">权限路径</label>
        <div class="layui-input-inline">
            <input type="text" name="power_url" required  lay-verify="required" placeholder="请输入权限路径" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">父级节点</label>
        <div class="layui-input-inline">
            <select name="power_parent_id" lay-verify="required">
                <option value="0">请选择</option>
                @foreach( $power as $v )
                    <option value="{{$v->power_id}}">{{$v->power_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">是否启用</label>
        <div class="layui-input-block">
                <input type="radio" name="status" value="1" title="启用" checked>
            <input type="radio" name="status" value="2" title="不启用" >
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="form">立即提交</button>
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
                url:'{{url('admin/power/create')}}',
                data:data.field,
                type:'post',
                dataType:'json',
                success:function( json_info ){
                    if( json_info.status == 200 ){
                        layer.open({
                            title: '权限'
                            ,content: '添加成功'
                        });
                        location.href='/admin/power/index'
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