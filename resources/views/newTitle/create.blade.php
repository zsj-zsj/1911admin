@include('public/header')
@include('public/left')

<div style="margin-top: 40px"></div>
<div style="margin-left: 200px">
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">新闻标题</label>
        <div class="layui-input-inline">
            <input type="text" name="title_name"  placeholder="新闻分类标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item ">
        <label class="layui-form-label">详细内容</label>
        <div class="layui-input-block">
            <textarea placeholder="请输入内容" name="title_detail"  class="layui-textarea" ></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">分类</label>
        <div class="layui-input-inline">
            <select name="cate_id" >
                <option value="">请选择</option>
                @foreach( $cate as $v )
                    <option value="{{$v->cate_id}}">{{$v->cate_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">图片</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="img">
                <i class="layui-icon">&#xe67c;</i>上传图片
            </button>
            <input type="hidden" id="imgimg" name="img">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-inline">
            <img src="" width="300px" id="imgss" alt="">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit type="button" lay-filter="form">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
</div>

<script>
    var $
    layui.use('upload', function(){
        var upload = layui.upload;
        var form = layui.form;
        //执行实例
        var uploadInst = upload.render({
            elem: '#img' //绑定元素
            ,url: '/admin/newTitle/upload/' //上传接口
            ,done: function(res){
                $('#imgimg').val(res.data);
                $("#imgss").attr('src',res.data);
            }
        });
    });
    layui.use('form', function(){

        var form = layui.form;
        $ = layui.jquery
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        form.on('submit(form)',function(data){
//            layer.msg(JSON.stringify(data.field));
            if( data.field.title_name == '' ){
                layer.msg('请输入标题');
                return false;
            }
            if( data.field.cate_id == '' ){
                layer.msg('请选择分类');
                return false;
            }
            $.ajax({
                url:'{{url('admin/newTitle/create')}}',
                data:data.field,
                type:'post',
                dataType:'json',
                success:function( json_info ){
                    if( json_info.status == 200 ){
                        layer.open({
                            title: '新闻分类标题'
                            ,content: '添加成功'
                        });
                        location.href='/admin/newTitle/index'
                    }else{
                        layer.open({
                            title: '新闻分类标题'
                            ,content: json_info.msg
                        });
                    }
                }
            })
            return false
        })
    })

</script>

@include('public/footer')