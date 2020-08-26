<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//不需要rbac
Route::get('/','Admin\IndexController@index');
Route::get('login','Admin\IndexController@login');
Route::post('login','Admin\IndexController@loginDo');
Route::get('quit','Admin\IndexController@quit');

//rbac 控制
Route::prefix('admin')->middleware('login')->group(function(){
    //权限
    Route::any('power/create','Admin\PowerController@create');  //添加
    Route::any('power/index','Admin\PowerController@index');    //列表
    Route::any('power/del','Admin\PowerController@del');    //删除
    Route::any('power/upd','Admin\PowerController@upd');    //修改
    //角色
    Route::any('role/create','Admin\RoleController@create');   //添加
    Route::any('role/index','Admin\RoleController@index');     //角色列表
    Route::any('role/roleDel','Admin\RoleController@roleDel');     //角色删除
    Route::any('role/roleUpd','Admin\RoleController@roleUpd');     //角色修改
    //角色权限
    Route::any('role/indexRP','Admin\RoleController@indexRP');        //角色权限列表
    Route::any('role/rpDel','Admin\RoleController@rpDel');            //角色权限删除
    //管理员
    Route::any('admin/create','Admin\AdminController@create');   //添加
    Route::any('admin/index','Admin\AdminController@index');    //列表
    Route::any('admin/del','Admin\AdminController@del');        //删除
    Route::any('admin/upd','Admin\AdminController@upd');        //修改
    Route::any('admin/selAdminRole','Admin\AdminController@selAdminRole');        //查看管理员角色

    //新闻分类标题
    Route::any('newCate/create','Admin\NewCateController@create');  //添加
    Route::any('newCate/index','Admin\NewCateController@index');    //列表
    Route::any('newCate/del','Admin\NewCateController@del');        //删除
    Route::any('newCate/upd','Admin\NewCateController@upd');        //修改
    //分类下的标题
    Route::any('newTitle/create','Admin\NewTitleController@create');  //添加
    Route::any('newTitle/index','Admin\NewTitleController@index');  //列表
    Route::any('newTitle/del','Admin\NewTitleController@del');      //删除
    Route::any('newTitle/upd','Admin\NewTitleController@upd');      //恢复
    Route::any('newTitle/upload','Admin\NewTitleController@upload');  //上传图片
    //新闻的留言列表
    Route::any('publish/list','Admin\PublishController@publish');  //列表
});