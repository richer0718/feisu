<?php
// 本类由系统自动生成，仅供测试用途
class SettingFourAction extends CommonAction {
    public function index(){
		$model = M('settingfour');
		$res = $model -> select();
		
		$this->assign('res',$res);// 赋值数据集
		
		$this -> display();
	}
	public function addSetting(){
		$this -> display();
	}
	
	public function submitSetting(){
		$model = M('settingfour');
		
		//检查是否重名
		$map['pre'] = $_POST['pre'];

		$is_name = $model -> where($map) -> find();

		if($is_name){
			$this -> error('此简称已存在');
		}
		
		$res = $model -> add($_POST);
		
		$data['pre'] = $_POST['pre'];
		$data['count'] = '0';
		M('makeidfour') -> add($data);
		
		if($res){
			$this -> success('添加成功','index');
		}
		
		
	}
	public function edit(){
		$model = M('settingfour');
		$map['id'] = $_GET['id'];
		$res = $model -> where($map) -> find();
		$this -> assign('res',$res);
		$this -> display();
	}
	public function submitEdit(){
		$model = M('settingfour');
		$map['id'] = $_POST['id'];
		$data['name'] = $_POST['name'];
		$data['pre'] = $_POST['pre'];
		$res = $model -> where($map) -> save($data);
		if($res){
			$this -> success('修改成功','index');
		}
	}
	
	public function delete(){
		$model = M('settingfour');
		$map['id'] = $_GET['id'];
		$res = $model -> where($map) -> delete();
		if($res){
			$this -> success('删除成功','__URL__/index');
		}
	}
	
	
}