@include('public/header')
@include('public/left')

<div style="margin-top: 10px" class="layui-body">
<table id="tab"  lay-filter="test"></table>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="upd">恢复</a>
</script>
</div>

<script>
    layui.use(['table','layer','form','element','jquery'], function(){
        var table = layui.table;
        var form = layui.form;
        var layer = layui.layer;
        var $ = layui.jquery;
        var element = layui.element;
        //第一个实例
        table.render({
            elem: '#tab',
            height: 480,
            url: '{{url('admin/newTitle/index')}}',
            page: true, //开启分页
            cols: [[ //表头
                {field: 'title_id', title: 'ID', sort: true, fixed: 'left'},
                {field: 'title_name', title: '新闻分类名称'},
                {field: 'img', title: '图片',templet:function(e){
                    return '<img src="'+ e.img +'">'
                }},
                {field: 'cate_name', title: '分类'},
                {field: 'statusss', title: '状态'},
                {fixed: 'right',title: '操作',align:'center', toolbar: '#barDemo'}
            ]]
        });

        table.on('tool(test)', function(obj){ //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
            var data = obj.data; //获得当前行数据
            var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
            var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）
           if(layEvent === 'del'){ //删除
                layer.confirm('确定删除吗？', function(index){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                    $.post(
                            "{{url('admin/newTitle/del')}}",
                            {title_id: data.title_id},
                            function (msg) {
                                if( msg.status == 200 ){
                                    layer.open({
                                        title: '新闻标题删除'
                                        ,content: '删除成功'
                                    });
                                }
                                location.href='/admin/newTitle/index'
                            },
                            'json'
                    )
                });
            }else if(layEvent === 'upd'){
               $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
               })
               $.post(
                       "{{url('admin/newTitle/upd')}}",
                       {title_id: data.title_id},
                       function (msg) {
                           if( msg.status == 200 ){
                               layer.open({
                                   title: '新闻标题详情'
                                   ,content: '恢复成功'
                               });
                           }
                           location.href='/admin/newTitle/index'

                       },
                       'json'
               )
           }
        });
    });
</script>


@include('public/footer')