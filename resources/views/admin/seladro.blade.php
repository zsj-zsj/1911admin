@include('public/header')
@include('public/left')

<div style="margin-top: 40px"></div>
<div style="margin-left: 200px">
    <form class="layui-form" action="">
        @foreach($data as $k=>$v)
        <div class="layui-form-item">
            <label class="layui-form-label">{{$v['admin_name']}}</label> <br>
                角色：{{$v['role_name']}}
        </div>
        @endforeach
    </form>
</div>
@include('public/footer')