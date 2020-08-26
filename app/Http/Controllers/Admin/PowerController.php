<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\PowerModel;

class PowerController extends CommonController
{
    public function create(Request $request)
    {
        if($request ->method() == 'POST'){
            if($request->post('power_parent_id')){
                $power_level = 2;
            }else{
                $power_level = 1;
            }
            $arr = [
                'power_name'=>$request->post('power_name'),
                'power_level'=>$power_level,
                'power_url'=>$request->post('power_url'),
                'status'=>$request->post('status'),
                'c_time'=>time(),
                'power_parent_id'=>$request->post('power_parent_id'),
            ];
            $resp = PowerModel::insertGetId($arr);
            if($resp){
                return $this->success();
            }else{
                return $this->fail('添加失败');
            }
        }
        $where = [
            ['status','=',1],
            ['power_level','=',1]
        ];
        $res = PowerModel::where($where)->get();
        return view('power.create',['power'=>$res]);
    }

    public function index(Request $request)
    {
        if($request->ajax()){
            $where=[
                ['status','<',3]
            ];
            $res = PowerModel::where($where)->paginate($request->get('limit'));
            $res = collect( $res ) -> toArray();
            foreach ($res['data'] as $k=>$v){
                $res['data'][$k]['c_time'] = date('Y-m-d H:i:s',$v['c_time']);
            }
            $count = PowerModel::where($where)->count();
            $list = [
                'code' => 0 ,
                'msg' => 'success',
                'count' => $count,
                'data' =>$res['data']
            ];
            return $list;
        }
        return view('power.index');
    }

    public function del(Request $request)
    {
        $id = $request->id;
        if(!$id){
            return $this->fail('缺少参数');
        }
        $where =[
            ['power_parent_id','=',$id],
            ['status','<',3]
        ];
        $count = PowerModel::where($where)->count();

        if($count > 0){
            return $this->fail('无法删除此权限');
        }
        $res = PowerModel::where(['power_id'=>$id])->update(['status'=>3]);
        if($res){
            return $this->success();
        }else{
            return $this->fail('删除失败');
        }
    }

    public function upd(Request $request)
    {
        if($request->method() == "POST" ){
            $arr = [
                'power_name'=>$request->power_name,
                'power_url'=>$request->power_url
            ];
            $data = PowerModel::where(['power_id'=>$request->power_id])->update($arr);
            if($data){
                return $this->success();
            }else{
                return $this->fail('修改失败');
            }
        }
        $id = $request->id;
        if(!$id){
            return $this->fail('缺少参数');
        }
        $res = PowerModel::where(['power_id'=>$id])->first();

        return view('power.upd',['power'=>$res]);
    }
}
