@include('public/header')
@include('public/left')

<div style="margin-top: 40px"></div>
<div style="margin-left: 200px">
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">角色名称</label>
        <div class="layui-input-inline">
            <input type="text" name="role_name" required  lay-verify="required" placeholder="请输入角色名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">是否启用</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="1" title="启用" checked>
            <input type="radio" name="status" value="0" title="不启用" >
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">权限管理</label>
        <div class="layui-input-block">
            @foreach($rolePower as $k=>$v)
                <div class="parent">
                    <input type="checkbox" name="like[]" lay-filter="parent"  value="{{$v['power_id']}}"
                           lay-skin="primary" title="{{$v['power_name']}}"><br/>
                    <div class="son">
                        @if(isset($v['son']) )
                        @foreach( $v['son'] as $key=>$val )
                                <input type="checkbox" name="like[]" lay-filter="son" value="{{$val['power_id']}}"
                                       lay-skin="primary" title="{{$val['power_name']}}">
                        @endforeach
                        @endif
                    </div>
                    {{--<hr>--}}
                </div>
            @endforeach
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="form">添加</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
</div>

<style>
    .son{
        margin-left: 40px;
        margin-top: 10px;
    }
</style>

<script>
    var $
    layui.use('form', function(){
        var form = layui.form
        $ = layui.jquery
        //父选子
        form.on('checkbox(parent)', function(data){
            if(  data.elem.checked == true  ){
                data.othis.parent('.parent').find('.son input').prop('checked', true);
            }else{
                data.othis.parent('.parent').find('.son input').prop('checked', false);
            }
            form.render()
        })
        //子选父
        form.on('checkbox(son)', function(data){
            if(  data.elem.checked == true  ){
                data.othis.parents('.parent').find('input').first().prop('checked', true);
            }else{
                // 先判断当前的子级有没有选中的，如果有选中的，不修改父级，如果全部都是不选中的，把父级也修改为不选中
                let mark = false;
                data.othis.parent('.son').find('input').each(function () {
                    console.log($(this).prop('checked'));
                    if( $(this).prop('checked') == true ){
                        mark = true;
                    }
                })
                if( mark == false )
                {
                    data.othis.parents('.parent').find('input').first().prop('checked', false);
                }
            }
            form.render();
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        form.on('submit(form)', function(data){
            layer.msg(JSON.stringify(data.field));
            $.ajax({
                url:'{{url('admin/role/create')}}',
                data:data.field,
                type:'post',
                dataType:'json',
                success:function( res ){
                   if(res.status != 200 ){
                       layer.open({
                           title: '角色权限',
                           content: res.msg
                       });
                   }else{
                       layer.open({
                           title: '角色权限',
                           content: '添加成功'
                       })
                       location.href='/admin/role/index'
                   }
                }
            })
            return false;
        })


    })

</script>
@include('public/footer')