<?php

namespace app\back\controller;

use think\Controller;
use think\Request;
use think\Db;

class Zhu extends Controller
{
    /**
     * 考试_后台用户登录接口
     *
     * @return \think\Response
     */
    public function login(Request $request)
    {
        //接口传递的所有参数
        $params=$request->param();
        //定义返回的格式
        $return = [
            'code'=>2000,
            'msg'=>'成功',
            'data'=>[]
        ];
        //判断用户名是否传递,或者用户名为空
        if(!isset($params['username'])||empty($params['username'])){
            $return = [
                'code'=>4001,
                'msg'=>'用户名不能为空'
            ];
            return json($return);
        }
        //判断密码是否传递,或者是否为空
         if(!isset($params['pwd'])||empty($params['pwd'])){
            $return = [
                'code'=>4001,
                'msg'=>'密码不能为空'
            ];
            return json($return);
        }
        $username = $params['username'];
        
        //是用户名去数据库中查询数据
        $user = Db::query('select * from ks_user where username = ?',[$username]);

        //用户不存在
        if(empty($user)){
             $return = [
                'code'=>4002,
                'msg'=>'用户不存在'
            ];
            return json($return);
        }else{
            //用户传递的密码
            $postpwd = md5($params['pwd']);
            //数据库查询出来的密码
            $dbPwd = $user[0]['pwd'];
            //密码不一致
            if($postpwd!= $dbPwd){
                 $return = [
                    'code'=>4003,
                    'msg'=>'用户密码错误',
                ];
                return json($return);
            }
            //过期时间
            $expired_at = date('Y-m-d H:i:s',time()+3600);

            //更新用户的token及token过期时间
            Db::query('update ks_user set token = replace(uuid(),"-",""),expired_at = ? where username = ?',[$expired_at, $username]);
            $userInfo  = Db::query('select token from ks_user where username=?',[$username]);
            $return['data']['token'] = $userInfo[0]['token'];
        }
        return json($return);
    }

        /*
        *获取用户列表的接口
        *@param $limit int
        *@param $sign string
        *@return json
        *
        *sgins签名
        */
        public function userList(Request $request){
            //接受传递的所有参数
            $params = $request->param();
            //定义返回的格式
            $return = [
                'code' => 2000,
                'msg' => '成功',
                'data' => []
            ];


            if(!isset($params['limit']) || empty($params['limit'])){
                $return = [
                    'code' => 4001,
                    'msg' => '参数不能为空'
                ];
                return json($return);
            }


            //校验用户的签名
            $result = $this->checkUserSign($params);


            if($result['code'] != 2000){
                return json($result);
            }


            //校验用户的签名
            $result = $this->checkUserSign($params);

            if($result['code'] != 2000){
                return json($result);
            }

            $userList = Db::query('select * from ks_user limit ?',[$params['limit']]);
            
            $return['data'] = $userList;


            return json($return);
        }

        /*
        *删除用户的接口
        *@param id int 用户的id
        *@param sign string
        *@param json
        */
        public function delUser(Request $request){
            //接受传递的所有参数
            $params = $request->param();
            //定义返回的格式
            $return = [
                'code' => 2000,
                'msg' => '成功',
                'data' =>[]
            ];

            if(!isset($params['id']) || empty($params['id'])){
                $return = [
                    'code' => 4001,
                    'msg' => '参数不能为空'
                ];
                return json($return);
            }

            //校验sign签名
            $result = $this->checkUserSign($params);
            if($result['code'] != 2000){
                return json($result);
            }
            try{
                //执行删除的操作
                Db::query('delete from ks_user where id=?',[$params['id']]);
            }catch(\Exception $e){
                $return = [
                    'code' => $e->getCode(),
                    'msg' => $e->getMessage()
                ];
                return json($return);
            }
            return json($return);
        }

        /*
        *校验用户sign签名加密
        *@param $params
        *@return array
        */
        public function checkUserSign($params){
            //sign 加密的秘钥
            $sercet = '1q2w3e!@#$';


            $return = [
                'code' => 2000,
                'msg' => '成功'
            ];

            if(!isset($params['sign']) || empty($params['sign'])){
                $retturn = [
                    'code' => 4001,
                    'msg' => '参数不能为空'
                ];
                return $return;
            }
            $sign = $params['sign'];
            unset($params['sign']);
            //生成我们自己的签名
            $string = http_build_query($params);

            $self_sign = md5($string.$sercet);

            //比较post传递的sign和我们自己生成签名是否一致


            if($sign != $self_sign){
                $return = [
                    'code' => '4006',
                    'msg' => 'sign签名不合法'
                ];
                return $return;
            }
            return $return;
        }

}
