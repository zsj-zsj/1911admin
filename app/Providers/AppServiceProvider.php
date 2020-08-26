<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Model\PowerModel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $route = '/'. request() ->path();
        //视图间共享数据
        $power_node = $this -> getRolePowerUrl();

        #第一个参数是模板，多个模板['aa','bb'] use中是传到闭包中的变量
        view()->composer('*',function($view)use($power_node,$route){
            $view->with(
                array(
                    'rolePowerUrl'=> $power_node,
                    'route' => $route
                )
            );
        });
    }

    public function getRolePowerUrl()
    {
        $where = [
            ['status','!=',3]
        ];
        $obj = PowerModel::where($where)->get();
        $powerArr = collect($obj)->toArray();
        $arr = [];
        foreach ($powerArr as $k=>$v){
            if($v['power_parent_id'] == 0){
                $arr[$v['power_id']] = $v;
            }else{
                $arr[$v['power_parent_id']]['son'][] = $v;
            }
        }
        return $arr;
    }
}
