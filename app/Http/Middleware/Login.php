<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\PowerModel;
use App\Model\AdminModel;

class Login
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userLogin = $this->getUserLogin();
        $route = $request->route()->uri();
        if(!$userLogin){
            return redirect('login');
        }
        $adminPower = $this->getAdminPower();
//        dd($adminPower);
        view()->composer('*',function($view)use($adminPower,$route){
            $view->with(
                array(
                    'rolePowerUrl'=> $adminPower,
                    'route' => $route
                )
            );
        });

        if( $this -> checkAdminPower() ){
            return $next($request);
        }else{
            return response() -> view('public.errorPow');
        }
    }

    /**
     * 获取session 登录状态
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     */
    private function getUserLogin()
    {
        return session('user');
    }

    /**
     * 获取管理员权限
     * @param int $is_show_level
     * @return mixed
     */
    public function getAdminPower( $is_show_level = 1 )
    {
        $admin_info = $this->getUserLogin();
        if( $admin_info['admin_type'] == 1 ){
            $this_admin_power = $this -> getSuperAdminPower( $is_show_level );
        }else{
            $this_admin_power = $this -> getAdminPowerByAdminId( $admin_info['admin_id'], $is_show_level );
        }

        return $this_admin_power;
    }

    /**
     * 超级管理员
     */
    public function getSuperAdminPower($is_show_level = 1)
    {
        $where=[
            [ 'status' , '!=' , 3 ]
        ];
        $res = PowerModel::where($where)->get()->toArray();
        $power = [];
        foreach ($res as $k=>$v) {
            if($v['power_parent_id'] == 0){
                $power[$v['power_id']] = $v;
            }else{
                $power[$v['power_parent_id']]['son'][] = $v;
            }
        }
        if( $is_show_level == 1 ){
            return $power;
        }else{
            return $res;
        }
    }

    /**
     * 检测用户是否有权限访问路由
     */
    public function checkAdminPower()
    {
        $route = request() -> route() ->uri();
        $power_list = $this->getAdminPower( 2 );
        $all_power = [];
        foreach( $power_list as $k => $v ){
            $all_power[] = $v['power_url'];
        }
        if( in_array( '/' . $route , $all_power ) ) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取普通管理员权限
     */
    public function getAdminPowerByAdminId($admin_id, $is_show_level = 1)
    {
        $where =[
            ['rbac_admin.admin_id','=',$admin_id],
            ['rbac_power.status','!=',3],
            ['rbac_role_power.rp_del','=',1]
        ];
        $res = AdminModel::join('rbac_admin_role','rbac_admin_role.admin_id','rbac_admin.admin_id')
                ->join('rbac_role','rbac_role.role_id','=','rbac_admin_role.role_id')
                ->join('rbac_role_power','rbac_role_power.role_id','=','rbac_role.role_id')
                ->join('rbac_power','rbac_power.power_id','=','rbac_role_power.power_id')
                ->where($where)
                ->get()
                ->toArray();
        $power = [];
        foreach ($res as $k=>$v) {
            if($v['power_parent_id'] == 0){
                $power[$v['power_id']] = $v;
            }else{
                $power[$v['power_parent_id']]['son'][] = $v;
            }
        }
        if( $is_show_level == 1 ){
            return $power;
        }else{
            return $res;
        }
    }

}
