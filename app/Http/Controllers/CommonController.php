<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    /**
     * 成功
     * @param array $data
     * @param string $msg
     * @param int $status
     * @return array
     */
    public function success($data=[],$msg='ok',$status=200)
    {
        $arr=[
            'data'=>$data,
            'msg'=>$msg,
            'status'=>$status,
        ];
        return $arr;
    }

    /**
     * 失败
     */
    public function fail($msg,$status=500,$data=[])
    {
        $arr=[
            'data'=>$data,
            'msg'=>$msg,
            'status'=>$status,
        ];
        return $arr;
    }


}
