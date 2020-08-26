@include('public/header')
@include('public/left')

<div style="margin-top: 40px"></div>
<div style="margin-left: 200px">
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">新闻分类</label>
        <div class="layui-input-inline">
            <input type="hidden" name="cate_id" value="{{$data->cate_id}}">
            <input type="text" name="cate_name" required value="{{$data->cate_name}}"  lay-verify="required" placeholder="新闻分类标题" autocomplete="off" class="layui-input">
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
                url:'{{url('admin/newCate/upd')}}',
                data:data.field,
                type:'post',
                dataType:'json',
                success:function( json_info ){
                    if( json_info.status == 200 ){
                        layer.open({
                            title: '新闻分类标题'
                            ,content: '修改成功'
                        });
                        location.href='/admin/newCate/index'
                    }
                }
            })
            return false
        })
    })

</script>

@include('public/footer')