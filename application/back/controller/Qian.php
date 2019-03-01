<?php

namespace app\back\controller;

use think\Controller;
use think\Request;
use think\Db;

class Qian extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }

    /**
     * 执行签到操作     *
     * @return \think\Response
     */
    public function doSign(Request $request)
    {
        //接受传递的参数
        $params=$request->param();

        $return = [
            'code '=>2000,
            'msg'=>'签到成功',
            'data'=>[]
        ];
       

        if(!isset($params['userid'])||empty($params['userid'])){
            $return =[
                'code'=>4001,
                'msg'=>'用户id不能为空'
            ]; 

            return json($return);
        }
        $userId = $params['userid'];

        // 获取今天的日期
        $today = date('Y-m-d');

        //根据当前用户id查询签到数据
        $sign1 = Db::query('select * from sign_info where userid = ?',[$userId]);

        if(!empty($sign1) && $sign1[0]['last_date']==$today){//重复签到
            $return = [
                'code'=>4001,
                'msg'=>'您已经签到过了,请明天再来'
            ];
            return json($return);
        }

        // //根据用户id查询签到的信息
        // $sign1 = Db::query('select * from sign_info where userid = ?',[$userId]);

        if(empty($sign1)){//第一次签到的时候
            Db::query('insert into sign_info(userid,c_date,total_scores,total_days,last_date) values(?,?,?,?,?)',[$userId,1,1,1,$today]);
            $return['data']['score']=1;
            return json($return);
        }else{
            //昨天的日期
            $last_day= date('Y-m-d',time()-3600*24);
            if($last_day == $sign1[0]['total_scores']){//连续注册
                //连续签到
                $c_days = $sign1[0]['c_date']+1;
            }else{
                $c_days=1;
            }

            $total_scores = $sign1[0]['total_scores']+$c_days;
            $total_days = $sign1[0]['total_days']+1;

            Db::query('update sign_info set c_date = ?,total_scores = ?,total_days = ?,last_date=? where userid = ?',[$c_days,$total_scores,$total_days,$today,$userId]);

            $return['data']['score'] = $c_days;

            return json($return);
        }
    }
    //签名的列表
    public function getList(){
        $sign = Db::query('select * from sign_info');

        $return = [
            'code'=>2000,
            'msg'=>'签到成功',
            'data'=>$sign
        ];
        return json($return);
    }

}
