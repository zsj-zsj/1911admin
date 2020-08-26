<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\NewCateModel;
use App\Model\NewTitleModel;

class NewTitleController extends CommonController
{
    public function create(Request $request)
    {
        if($request->method() == "POST"){
            $cate_id = $request->cate_id;
            $title_name = $request->title_name;
            $img = $request->img;
            $title_commont = $request->title_detail;
            if(!$title_name){
                return $this->fail('请输入标题');
            }
            if(!$cate_id){
                return $this->fail('请选择分类');
            }
            if(!$img){
                return $this->fail('请上传图片');
            }
            if(!$title_commont){
                return $this->fail('请填写新闻详情');
            }
            $arr = [
                'title_name'=>$title_name,
                'img'=>$img,
                'c_times'=>time(),
                'cate_id'=>$cate_id,
                'title_detail'=>$title_commont,
            ];
            $res = NewTitleModel::insert($arr);
            if($res){
                return $this->success();
            }else{
                return $this->fail('添加失败');
            }

        }
        $cate = NewCateModel::where(['status'=>1])->get();
        return view('newTitle.create',['cate'=>$cate]);
    }

    public function upload(Request $request)
    {
        $file = $_FILES['file'];
        $tmpName = $file['tmp_name'];
        $ext = explode(".",$file['name'])[1];
        $newFileName  = md5(uniqid()).".".$ext;
        $path = './image/'.$newFileName;
        move_uploaded_file($tmpName,$path);
        $r = ltrim($path,'.');
        return $arr=[
            'data'=>$r
        ];
    }

    public function index(Request $request)
    {
        if($request->ajax()){
            $res = NewTitleModel::join('new_category','new_category.cate_id','=','new_title.cate_id')
                        ->paginate($request->get('limit'));
            $res = collect( $res ) -> toArray();
            $count = NewTitleModel::join('new_category','new_category.cate_id','=','new_title.cate_id')->count();
            foreach ($res['data'] as $k=>$v){
                if( $res['data'][$k]['statusss'] == 1){
                    $res['data'][$k]['statusss'] = '可以评论';
                }else{
                    $res['data'][$k]['statusss'] = '不能评论';
                }
                $res['data'][$k]['c_times'] = date('Y-m-d H:i:s',$v['c_times']);
                $res['data'][$k]['img'] = env('APP_URL').$v['img'];
            }
            $list = [
                'code' => 0 ,
                'msg' => 'success',
                'count' => $count,
                'data' =>$res['data']
            ];
            return $list;
        }
        return view('newTitle.index');
    }

    public function del(Request $request)
    {
        $id = $request->title_id;
        if(!$id){
            return $this->fail('缺少参数');
        }
        $res = NewTitleModel::where(['title_id'=>$id])->update(['statusss'=>2]);
        if($res){
            return $this->success();
        }
    }

    public function upd(Request $request)
    {
        $id = $request->title_id;
        if(!$id){
            return $this->fail('缺少参数');
        }
        $res = NewTitleModel::where(['title_id'=>$id])->update(['statusss'=>1]);
        if($res){
            return $this->success();
        }
    }
}
