@include('public/header')
@include('public/left')

<div style="margin-top: 10px" class="layui-body">
<table id="tab"  lay-filter="test"></table>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
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
            url: '{{url('admin/newCate/index')}}',
            page: true, //开启分页
            cols: [[ //表头
                {field: 'cate_id', title: 'ID', sort: true, fixed: 'left'},
                {field: 'cate_name', title: '新闻分类名称'},
                {field: 'c_time', title: '创建时间', sort: true},
                {fixed: 'right',title: '操作',align:'center', toolbar: '#barDemo'}
            ]]
        });

        table.on('tool(test)', function(obj){ //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
            var data = obj.data; //获得当前行数据
            var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
            var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）
           if(layEvent === 'del'){ //删除
                layer.confirm('确定删除吗？', function(index){
                    obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                    layer.close(index);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                    //向服务端发送删除指令
                    $.post(
                            "{{url('admin/newCate/del')}}",
                            {cate_id: data.cate_id},
                            function (msg) {
                                if( msg.status == 200 ){
                                    layer.open({
                                        title: '新闻标题删除'
                                        ,content: '删除成功'
                                    });
                                }
                            },
                            'json'
                    )
                });
            }else if(layEvent === 'edit'){
               location.href="{{url('admin/newCate/upd')}}?cate_id="+data.cate_id;
            }
        });
    });

</script>

@include('public/footer')