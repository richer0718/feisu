<?php
// 本类由系统自动生成，仅供测试用途
class NewsAction extends CommonAction {
    public function index(){
		$model = M('news');
		
		
		
		import('ORG.Util.Page');// 导入分页类
		$count      = $model->count();// 查询满足要求的总记录数
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $model->where($map)->order('ishead desc,publishtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
	}
	
	public function addNews(){
		$this -> display();
	}
	
	public function submitNews(){
		$model = M('news');
		$data['content'] = $_POST['content'];
		$data['ishead'] = $_POST['ishead'];
		$data['publishtime'] = time();
		$data['title'] = $_POST['title'];
		$res = $model -> add($data);
		if($res){
			$this -> success('发布成功','index');
			
		}else{
			$this -> error('失败');
		}
	}
	
	public function newsInfo(){
		$model = M('news');
		$map['id'] = $_GET['id'];
		$res = $model -> where($map) -> find();
		$this -> assign('res',$res);
		$this -> display();
	}
	
	public function deleteNews(){
		$model = M('news');
		$map['id'] = $_GET['id'];
		$res = $model -> where($map) -> delete();
		if($res){
			$this -> success('删除成功','__URL__/index');
		}else{
			$this -> error('删除失败');
		}
	}
	
	public function editNews(){
		$model = M('news');
		$map['id'] = $_GET['id'];
		$res = $model -> where($map) -> find();
		$this -> assign('res',$res);
		$this -> display();
	}
	
	public function submitEditNews(){
		$model = M('news');
		$map['id'] = $_POST['id'];
		$data['title'] = $_POST['title'];
		$data['ishead'] = $_POST['ishead'];
		$data['content'] = $_POST['content'];
		$data['publishtime'] = time();
		$res = $model -> where($map) -> save($data);
		if($res !== false){
			$this -> success('修改成功','index');
		}else{
			$this -> error('修改失败');
		}
	}
	
}