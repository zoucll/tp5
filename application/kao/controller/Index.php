<?php  
	namespace app\kao\controller;
	use think\Db;
	use think\Controller;
	use think\Model;

	class Index extends Controller{
		public function add(){
			if(request()->isPost()){
				$data = input();
				$res = db('kao')->insert($data);
				if($res){
					$this->success('添加成功',url('show'));
				}else{
					$this->error('添加失败');
				}
			}else{
				return $this->fetch('add');
			}
		}
		public function show(){
			$totab = db('kao')->count();
			$listshow = 2;
			$page_model = new \Page($totab,$listshow);
			$page = $page_model->fpage(array(1,2,3,4,5,6,7,8));
			$lists = db('kao')->pageinate(2);
			$this->assign('page',$page);
			$this->assign('lists',$lists);
			return $this->fetch('show');
		}
	}

?>