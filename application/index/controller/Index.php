<?php  
	namespace app\index\controller;
	use think\Controller;
	use think\Request;

	class Index extends Controller{
		/**
		 * 显示资源列表
		 * @return \think\Response
		 */
		public function index(){
			$data = [
				'nav_num'=>3
			];
			$nav = $this->HttpCurl('http://www.tp4.com/index/nav',true,$data);
			return $this->fetch();
		}
		/**
		 * desc 商品详情页面
		 */
		public function detail(Request $request){
			$params = $request->param();
			$gid = isset($params['id'])?$params['id']:0;
			return $this->fetch();
		}
	}

?>