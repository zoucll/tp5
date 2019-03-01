<?php

namespace app\back\controller;

use think\Controller;
use think\Request;

class Products extends Controller
{
    /**
     * 商品详情的接口
     * @param $gid int 商品id
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        //接受请求的参数
        $params = $this->param();

        //接口返回的格式
        $return = [
            'code'=>2000,
            'msg'=>'成功',
            'data'=>[]
        ];
        //判断参数是否传递
        if(!isset($params['nav_num'])||empty($params['nav_num'])){
            $return = [
                'code'=>4001,
                'msg'=>'参数不全'
            ];
            return json($return);
        }

    }

   
}
