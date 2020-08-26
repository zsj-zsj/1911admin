<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\AdminModel;
use App\Model\AdminRoleModel;
use App\Model\RoleModel;
use Illuminate\Validation\Rule;

class AdminController extends CommonController
{
    public function create(Request $request)
    {
        if($request->method() == "POST"){
            $name = $request->admin_name;
            $email = $request->admin_email;
            $pwd = $request->admin_pwd;
            $pwds = $request->admin_pwds;
            $mobile = $request->admin_mobile;
            $admin_type = $request -> post('admin_type')??2;

            if(!$name){
                return $this->fail('请输入管理员姓名');
            }
            $count = AdminModel::where(['admin_name'=>$name])->count();
            if($count > 0){
                return $this->fail('用户名已拥有');
            }
            if(!$email){
                return $this->fail('请输入邮箱');
            }
            $emPreg = '/^([\w\.\_]{2,10})@(\w{1,}).([a-z]{2,4})$/';
            if(!preg_match( $emPreg, $email )){
                return $this->fail('邮箱格式不对');
            }
            if(!$mobile){
                return $this->fail('请输入手机号');
            }
            $preg = '/^1{1}\d{10}$/';
            if(! preg_match_all($preg,$mobile)){
                return $this->fail('手机号格式不对');
            }
            if(!$pwd){
                return $this->fail('请输入密码');
            }
            if($pwd != $pwds){
                return $this->fail('密码不一致');
            }
            $roleArr = $request ->post('role') ?? [];
            if( $admin_type  == 2 ){
                if( empty( $roleArr ) ){
                    return $this -> fail('请选择管理员对应的角色');
                }
            }

            try{
                $adminInfo=[
                    'admin_name'=>$name,
                    'admin_pwd'=>password_hash($pwd,PASSWORD_BCRYPT),
                    'admin_email'=>$email,
                    'admin_mobile'=>$mobile,
                    'admin_type'=>$admin_type,
                    'c_time'=>time(),
                    'admin_status'=>1
                ];
                $admin = AdminModel::insertGetId($adminInfo);
                if(!$admin){
                    throw new \Exception('管理员表添加失败');
                }

                if($admin_type == 2 ){
                    foreach($roleArr as $k=>$v){
                        $adminRole=[
                            'admin_id'=>$admin,
                            'role_id'=>$v
                        ];
                        $res = AdminRoleModel::create($adminRole);
                        if(!$res){
                            throw new \Exception('角色管理员入库失败');
                        }
                    }
                }
                return $this->success();
            }catch (\Exception $e) {
                DB::rollBack();
                $msg = $e ->getMessage();
                return $this -> fail( $msg );
            }
        }

        // 角色 对应的角色权限
        $rolePower = RoleModel::where(['rbac_role.status'=>1])
                ->join('rbac_role_power','rbac_role.role_id','=','rbac_role_power.role_id')
                ->join('rbac_power','rbac_role_power.power_id','=','rbac_power.power_id')
                ->get()->toArray();

        $role = [];
        foreach($rolePower as $k=>$v){
            if($v['power_level'] == 1 ){
                if(!isset($role[$v['role_id']])){
                    $role[$v['role_id']] = $v;
                }else{
                    $role[$v['role_id']]['power_list'][] = $v;
                }
            }else{
                $role[$v['role_id']]['power_list'][] = $v;
            }
        }
        return view('admin.create',['rolePower'=>$role]);
    }

    public function index(Request $request)
    {
        if($request->ajax()){
            $res = AdminModel::paginate($request->get('limit'));
            $res = collect( $res ) -> toArray();
            foreach ($res['data'] as $k=>$v){
                $res['data'][$k]['c_time'] = date('Y-m-d H:i:s',$v['c_time']);
                if($res['data'][$k]['admin_type'] == 1){
                     $res['data'][$k]['admin_type'] = '超级管理员';
                }else{
                    $res['data'][$k]['admin_type'] = '普通管理员';
                }
                if($res['data'][$k]['admin_status'] == 1){
                    $res['data'][$k]['admin_status'] = '待审核';
                }elseif($res['data'][$k]['admin_status'] ==2 ){
                    $res['data'][$k]['admin_status'] = '锁定或已删除';
                }else{
                    $res['data'][$k]['admin_status'] = '正常';
                }
            }
            $count = AdminModel::count();
            $list = [
                'code' => 0 ,
                'msg' => 'success',
                'count' => $count,
                'data' =>$res['data']
            ];
            return $list;
        }
        return view('admin.index');
    }

    public function del(Request $request)
    {
        $id = $request->id;
        if(!$id){
            return $this->fail('缺少参数');
        }
        $res = AdminModel::where(['admin_id'=>$id])->update(['admin_status'=>2]);
        if($res){
            return $this->success();
        }else{
            return $this->fail('删除失败');
        }
    }

    public function upd(Request $request)
    {
        if($request->method() == "POST"){
            $id = $request->admin_id;
            $arr = [
                'admin_name'=>$request->admin_name,
                'admin_email'=>$request->admin_email,
                'admin_mobile'=>$request->admin_mobile,
                'admin_status'=>$request->admin_status,
            ];
            $emPreg = '/^([\w\.\_]{2,10})@(\w{1,}).([a-z]{2,4})$/';
            if(!preg_match( $emPreg, $request->admin_email )){
                return $this->fail('邮箱格式不对');
            }
            $preg = '/^1{1}\d{10}$/';
            if(! preg_match_all($preg,$request->admin_mobile)){
                return $this->fail('手机号格式不对');
            }
            $res = AdminModel::where(['admin_id'=>$id])->update($arr);
            if($res){
                return $this->success();
            }else{
                return $this->fail('修改失败');
            }
        }
        $id = $request->id;
        if(!$id){
            return $this->fail('缺少参数');
        }
        $adminInfo = AdminModel::find($id);
        return view('admin.upd',['data'=>$adminInfo]);
    }

    public function selAdminRole(Request $request)
    {
        $id = $request->id;
        if(!$id){
            return $this->fail('缺少参数');
        }
        $res = AdminRoleModel::where(['rbac_admin.admin_id'=>$id])
            ->join('rbac_admin','rbac_admin.admin_id','=','rbac_admin_role.admin_id')
            ->join('rbac_role','rbac_role.role_id','=','rbac_admin_role.role_id')
            ->select('role_name','admin_name')
            ->get()->toArray();

        return view('admin.seladro',['data'=>$res]);
    }



}
