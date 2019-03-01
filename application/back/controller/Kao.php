<?php

namespace app\back\controller;

use think\Controller;
use think\Request;
use think\Db;

class Kao extends Controller
{
    /**
     * 服务端评论接口
     *
     * @return \think\Response
     */
    public function userList(Request $request)
    {
        //接口接受参数
        $params = $request->param();
        //接口校验
        $return = [
            'code'=>2000,
            'msg'=>'成功',
            'data'=>[];
        ];
        if(!isset($params['ping'])||empty($params['ping'])){
            $return = [
                'code'=>4001,
                'msg'=>'评论不能为空'
            ];

        }
        return json($request);

    }
    /**
     * sign签名接口
     * @return \think\Response
     * 
     */
    public  function pingSign(Request $request){
        //接收参数
        $params = $request->param();

        $return = [
            'code'=>2000,
            'msg'=>'成功'，
            'data'=[]
        ];
        //sign签名认证
        if(!isset($params['sign'])||empty($params['sign'])){
            return =[
                'code'=>4001,
                'msg'=>'参数不全'
            ];
         return json($return);
        }



   
}
