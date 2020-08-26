<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\NewCateModel;

class NewCateController extends CommonController
{
    public function create(Request $request)
    {
        if($request->method() == "POST"){
            $arr=[
                'cate_name'=>$request->post('cate_name'),
                'c_time'=>time(),
                'status'=>1,
            ];
            $res = NewCateModel::create($arr);
            if($res){
                return $this->success();
            }else{
                return $this->fail('添加失败');
            }
        }
        return view('newCate.create');
    }

    public function index( Request $request )
    {
        if($request->ajax()){
            $res = NewCateModel::where(['status'=>1])->paginate($request->get('limit'));
            $res = collect( $res ) -> toArray();
            $count = NewCateModel::where(['status'=>1])->count();
            foreach ($res['data'] as $k=>$v){
                $res['data'][$k]['c_time'] = date('Y-m-d H:i:s',$v['c_time']);
            }
            $list = [
                'code' => 0 ,
                'msg' => 'success',
                'count' => $count,
                'data' =>$res['data']
            ];
            return $list;
        }
        return view('newCate.index');
    }

    public function del(Request $request)
    {
        $id = $request->cate_id;
        if(!$id){
            return $this->fail('缺少参数');
        }
        $res = NewCateModel::where(['cate_id'=>$id])->update(['status'=>2]);
        if($res){
            return $this->success();
        }else{
            return $this->fail('删除失败');
        }
    }

    public function upd(Request $request)
    {
        if($request->method() == "POST" ){
            $cate_id = $request->cate_id;
            $cate_name = $request->cate_name;
            if(!$cate_id || !$cate_name ){
                return $this->fail('缺少参数');
            }
            $updName = NewCateModel::where(['cate_id'=>$cate_id])->update(['cate_name'=>$cate_name]);
            if($updName){
                return $this->success();
            }else{
                return $this->fail('修改失败');
            }
        }
        $id = $request->cate_id;
        if(!$id){
            return $this->fail('缺少参数');
        }
        $res = NewCateModel::where(['cate_id'=>$id])->first();
        return view('newCate.upd',['data'=>$res]);
    }

}
