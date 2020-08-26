@include('public/header')
@include('public/left')

<div style="margin-top: 10px" class="layui-body">
    <table id="tab"  lay-filter="test"></table>
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
            url: '{{url('admin/publish/list')}}',
            page: true, //开启分页
            cols: [[ //表头
                {field: 'p_id', title: 'ID', sort: true, fixed: 'left'},
                {field: 'p_text', title: '评论内容'},
                {field: 'p_time', title: '评论时间'},
                {field: 'user_name', title: '用户'},
                {field: 'title_name', title: '新闻'},
            ]]
        });
    });

</script>

@include('public/footer')