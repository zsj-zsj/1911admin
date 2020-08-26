<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\RoleModel;
use App\Model\RolePowerModel;
use App\Model\PowerModel;
use Illuminate\Support\Facades\DB;

class RoleController extends CommonController
{
    public function create(Request $request)
    {
        if($request->method() == 'POST'){
            $role_name = $request->role_name;
            if(!$role_name){
                return $this->fail('请选择角色名称');
            }
            $status = $request->status;
            if(!$status){
                return $this->fail('请选择状态');
            }
            $powerList = $request->like;
            if(!$powerList){
                return $this->fail('请选择权限结点');
            }
            $count = RoleModel::where(['role_name'=>$role_name])->count();
            if($count>0){
                return $this->fail('角色已拥有');
            }

            try{
                DB::beginTransaction();
                //写入角色表
                $roleArr = [
                    'role_name'=>$role_name,
                    'status'=>$status,
                    'c_time'=>time()
                ];
                $role_id = RoleModel::insertGetId($roleArr);
                if(!$role_id){
                    throw new \Exception('写入角色表失败');
                }

                foreach ($powerList as $k=>$v){
                    $rolePowerArr = [
                        'role_id'=>$role_id,
                        'power_id'=>$v
                    ];
                    $res = RolePowerModel::insert($rolePowerArr);
                    if(!$res){
                        throw new \Exception('写入角色权限表失败');
                    }
                }
                DB::commit();
                return $this -> success();
            }catch (\Exception $e){
                DB::rollBack();
                return $this -> fail( $e -> getMessage() );
            }
        }
        $rolePower = $this->getRolePower();
        return view('role.create',['rolePower'=>$rolePower]);
    }

    //递归 获取结点对应权限
    private function getRolePower()
    {
        $where=[
            ['status','!=',3],
        ];
        $res = PowerModel::where($where)->get();
        $rolePower = collect($res)->toArray();
        $arr = [];
        foreach($rolePower as $k=>$v){
            if( $v['power_parent_id'] == 0 ){
                $arr[$v['power_id']] = $v;
            }else{
                $arr[$v['power_parent_id']]['son'][] = $v;
            }
        }
        return $arr;
    }

    /**
     * 角色列表
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $res = RoleModel::where(['status'=>1])->paginate($request->get('limit'));
            $res = collect( $res ) -> toArray();
            foreach ($res['data'] as $k=>$v){
                $res['data'][$k]['c_time'] = date('Y-m-d H:i:s',$v['c_time']);
            }
            $count = RoleModel::where(['status'=>1])->count();
            $list = [
                'code' => 0 ,
                'msg' => 'success',
                'count' => $count,
                'data' =>$res['data']
            ];
            return $list;
        }
        return view('role.index');
    }

    /**
     * 角色删除
     * @param Request $request
     * @return array
     */
    public  function roleDel(Request $request)
    {
        $role_id = $request->role_id;
        if(!$role_id){
            return $this->fail('缺少参数');
        }
        $where = [
            'role_id'=>$role_id,
            'status'=>1
        ];
        $count = RolePowerModel::where($where)->count();
        if($count > 0){
            return $this->fail('此角色下有权限无法删除');
        }else{
            $res = RoleModel::where(['role_id'=>$role_id])->update(['status'=>2]);
            if($res){
               return $this->success();
            }else{
                return $this->fail('删除失败');
            }
        }
    }

    /**
     * 角色修改
     *
     */
    public function roleUpd(Request $request)
    {
        if($request->method() == "POST" ){
            $id = $request->role_id;
            $name = $request->role_name;
            if(!$id || !$name){
                return $this->fail('缺少参数');
            }
            $res = RoleModel::where(['role_id'=>$id])->update(['role_name'=>$name]);
            if($res){
                return $this->success();
            }else{
                return $this->fail('修改失败');
            }
        }

        $role_id = $request->role_id;
        if(!$role_id){
            return $this->fail('缺少参数');
        }
        $role = RoleModel::where(['role_id'=>$role_id])->first();
        return view('role.roleupd',['role'=>$role]);
    }

    /**
     *角色权限列表
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexRP(Request $request)
    {
        if($request->ajax()){
            $res = RolePowerModel::where(['rp_del'=>1])
                ->join('rbac_role','rbac_role.role_id','=','rbac_role_power.role_id')
                ->join('rbac_power','rbac_power.power_id','=','rbac_role_power.power_id')
                ->paginate($request->get('limit'));
            $res = collect( $res ) -> toArray();
            foreach ($res['data'] as $k=>$v){
                $res['data'][$k]['c_time'] = date('Y-m-d H:i:s',$v['c_time']);
            }
            $count = RolePowerModel::count();
            $list = [
                'code' => 0 ,
                'msg' => 'success',
                'count' => $count,
                'data' =>$res['data']
            ];
            return $list;
        }
        return view('role.indexRP');
    }

    public function rpDel(Request $request)
    {
        $id = $request->id;
        if(!$id){
            return $this->fail('缺少参数');
        }
        $res = RolePowerModel::where(['id'=>$id])->update(['rp_del'=>2]);
        if($res){
            return $this->success();
        }else{
            return $this->fail('删除失败');
        }
    }

}
