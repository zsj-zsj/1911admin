<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\PublishModel;
use App\Model\NewTitleModel;


class PublishController extends CommonController
{
    public function publish(Request $request)
    {
        if($request->ajax()) {
            $res = PublishModel::where(['p_status' => 1])
                ->join('new_title','new_title.title_id','=','u_publish.title_id')
                ->join('u_user','u_user.user_id','=','u_publish.user_id')
                ->paginate($request->get('limit'));
            $res = collect($res)->toArray();
            $count = PublishModel::where(['p_status' => 1])
                ->join('new_title','new_title.title_id','=','u_publish.title_id')
                ->join('u_user','u_user.user_id','=','u_publish.user_id')
                ->count();
            foreach ($res['data'] as $k => $v) {
                $res['data'][$k]['p_time'] = date('Y-m-d H:i:s', $v['p_time']);
            }
            $list = [
                'code' => 0,
                'msg' => 'success',
                'count' => $count,
                'data' => $res['data']
            ];
            return $list;
        }
        return view('publish.list');
    }
}
