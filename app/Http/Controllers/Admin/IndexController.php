<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\AdminModel;

class IndexController extends CommonController
{
    public function index()
    {
        return view('public.index');
    }

    public function login()
    {
        return view('admin.login');
    }

    public function loginDo(Request $request)
    {
        $name = $request->admin_name;
        $pwd = $request->admin_pwd;
        if(!$name){
            return $this->fail('请输入用户名');
        }
        if(!$pwd){
            return $this->fail('请输入密码');
        }
        $userInfo = AdminModel::where(['admin_name'=>$name])->first();
        if(!$userInfo){
            return $this->fail('用户不存在');
        }
        $pwds = password_verify($pwd,$userInfo->admin_pwd);
        if(!$pwds){
            return $this->fail('密码不正确');
        }
        if($userInfo->admin_status == 1 ){
            return $this->fail('用户未审核，无法登录');
        }elseif($userInfo->admin_status == 2 ){
            return $this->fail('用户锁定，无法登录');
        }else{
            session(['user'=>$userInfo->toArray()]);
            return $this->success();
        }
    }

    public function quit(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }
}
