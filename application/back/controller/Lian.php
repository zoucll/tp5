<?php

namespace app\back\controller;

use think\Controller;
use think\Request;

class Lian extends Controller
{
    /**
     * 获取用户列表的接口
     *
     * @return \think\Response
     */
    public function userList(Request $request)
    {
        //接受传递的参数
        $params = $request->params();
        //定义反回的格式
        $return = [
            'code'=>2000,
            'msg'=>
        ];

    }

    
}
