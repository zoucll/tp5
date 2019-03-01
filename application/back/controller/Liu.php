<?php

namespace app\back\controller;

use think\Controller;
use think\Request;
use think\Db;

class Liu extends Controller
{
    /**
     * @desc首页导航栏的接口
     * @param nav_num int
     *
     * @return \think\Response
     */
    public function nav(Request $request)
    {
        //接受请求的参数
        $params = $request->param();

        //生成签名函数
       $result= $this->createSign($params);
       
       if($result['code']!=2000){
         return json($result);
       }

        //接口返回的格式
        $return = [
            'code'=>2000,
            'msg'=>'成功',
            'data'=>[]
        ];

        //判断参数是否传递
        if(!isset($params['nav_num'])||empty($params['nav_num'])){
            $return =[
                'code'=>4001,
                'msg'=>'参数不全'
            ];
            return json($return);
        }

        //限制导航输出的条数
        $num = $params['nav_num'];

        //查询导航栏数据
        $nav_list= Db::query('select id,name,url from bp_nav  limit ?',[$num]);
        $return['data']=$nav_list;
        return json($return);
    }

    /**
     * @desc首页banner的接口
     * @param b_num int
     *
     * @return \think\Response
     */
    public function banner(Request $request){
        //接受请求参数
        $params = $request->param();

        //生成签名函数
        $result = $this->createSign($params);
        if($result['code']!=2000){
            return json($result);
        }
        //接口返回格式
        $return = [
            'code'=>2000,
            'msg'=>'成功',
            'data'=>[]
        ];

        //判断参数是否传递
        if(!isset($params['b_num'])||empty($params['b_num'])){
            $return = [
                'code'=>4001,
                'msg'=>'参数不全'
            ];
            return json($return);
        }
        //限制banner 输出的条数
        $num = $params['b_num'];

        //查询banner 输出的条数
        $banner_list = Db::query('select name,image_url,url from bp_ad where cate_id = 1 limit ? ',[$num]);
        $return['data']=$banner_list;
        return json($return);
    }
    /**
    * @desc首页商品列表的接口
    * @param g_num int 商品属性
    * @param tags int  商品标识
    * @return \think\Response 
     */
    public function goods(Request $request){
        //接受请求的参数
        $params = $request->param();

        //生成签名函数
        $result = $this->createSign($params);

        if($result['code']!=2000){
            return json($result);
        }

        //接口返回格式
        $return = [
            'code'=>2000,
            'msg'=> '成功',
            'data'=>[]
        ];
        //判断参数是否传递
        if(!isset($params['g_num'])||empty($params['g_num'])){
            $return=[
                'code'=>4001,
                'msg'=>'参数不全'
            ];
            return json($return);
        }

        if(!isset($params['tags'])||empty($params['tags'])){
            $return=[
                'code'=>4001,
                'msg'=>'参数不全'
            ];
            return json($return);
        }
        $num = $params['g_num'];//限制数量
        $tags = $params['tags'];//类别

        //实例化redis对象
        $redis = new \Redis();
        $redis->connect('127.0.0.1',6379);

        $key = 'goods_'.$tags; //定义好要缓存人key值
        $goodsDada = $redis->get($key);//获取缓存内容

        if(empty($goodsData)){//缓存没有内容的时候
            $good = Db::query('select id,goods_name,goods_image,shop_price,maket_price,level form bp_goods where tags = ? limit ?',[$tags,$num]);
            $redis->serex($key,3600,json_encode($goods));//把数据存储到redis的缓存中

            Log::info('首页商城列表的接口:从数据库中读取数据');

        }else{//redis缓存有数据的时候
            $goodd = json_decode($goodsData,true);//把缓存的数据转成数组

            Log::info('首页商品列表的接口:从redis中读取数据');

        }

        $return['data'] = $goods;
        return json($return);
    }

    public function createQueue(){
        $arr = [
            '15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701','15004693701'
        ];
        $array = array_chunk($arr,3);//把数组按照三条进行拆分

        $redis= new \Redis();
        $redis->connect('127.0.0.1',6379);
        $query = 'phone_queue';//redis存储的key

        foreach ($array as $key => $value) {
            $redis->lpush($queue,json_encode($value));

            log::info('队列生产'.$key.": ".json_encode($value));
        }
        return '队列插入成功';
    }

}
