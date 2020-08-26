<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
        <ul class="layui-nav layui-nav-tree"  lay-filter="test">
            @foreach($rolePowerUrl as $k=>$v)
                @if(strstr($route,$v['power_url']) != false)
                    <li class="layui-nav-item layui-nav-itemed">
                @else
                    <li class="layui-nav-item">
                @endif
            <li class="layui-nav-item">
                <a href="javascript:;">{{$v['power_name']}}</a>
                <dl class="layui-nav-child">
                    @if( isset($v['son']) )
                    @foreach($v['son'] as $key=>$val)
                    <dd><a href="{{$val['power_url']}}">{{$val['power_name']}}</a></dd>
                    @endforeach
                    @endif
                </dl>
            </li>
            @endforeach
        </ul>
    </div>
</div>