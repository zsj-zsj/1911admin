@include('public/header')
@include('public/left')

<div style="margin-top: 10px" class="layui-body">
    <table id="tab"  lay-filter="test"></table>
    <script type="text/html" id="barDemo">
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
            url: '{{url('admin/role/indexRP')}}',
            page: true, //开启分页
            cols: [[ //表头
                {field: 'id', title: 'ID', sort: true, fixed: 'left'},
                {field: 'role_name', title: '角色名称'},
                {field: 'power_name', title: '权限名称'},
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
                    //向服务端发送删除指令
                    $.post(
                            "{{url('admin/role/rpDel')}}",
                            {id: data.id},
                            function (msg) {
                                if( msg.status == 200 ){
                                    layer.open({
                                        title: '角色权限'
                                        ,content: '删除成功'
                                    });
                                    obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                                    layer.close(index);
                                }else{
                                    layer.open({
                                        title: '角色权限',
                                        content: msg.msg
                                    });
                                }
                            },
                            'json'
                    )
                });
            }
        })
    });

</script>

@include('public/footer')