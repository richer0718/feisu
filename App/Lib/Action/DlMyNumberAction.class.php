<?php
// 本类由系统自动生成，仅供测试用途
class DlMyNumberAction extends DlCommonAction {
	
	
    public function index(){

		$model = M('Number');
		
		import('ORG.Util.Page');// 导入分页类
		
		
		
		$map['belongid'] = $_SESSION['login_user'];
		
		$count      = $model->where($map)->count();// 查询满足要求的总记录数
		
		$Page       = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		
		$show       = $Page->show();// 分页显示输出
		
		$list = $model->where($map)->order('status asc , buytime desc ,id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
	}
	
	//benghuai
	public function indextwo(){

		$model = M('Numbertwo');
		
		import('ORG.Util.Page');// 导入分页类
		
		
		
		$map['belongid'] = $_SESSION['login_user'];
		
		$count      = $model->where($map)->count();// 查询满足要求的总记录数
		
		$Page       = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		
		$show       = $Page->show();// 分页显示输出
		
		$list = $model->where($map)->order('status asc , buytime desc ,id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
	}
	public function indexthree(){

		$model = M('Numberthree');
		
		import('ORG.Util.Page');// 导入分页类
		
		
		
		$map['belongid'] = $_SESSION['login_user'];
		
		$count      = $model->where($map)->count();// 查询满足要求的总记录数
		
		$Page       = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		
		$show       = $Page->show();// 分页显示输出
		
		$list = $model->where($map)->order('status asc , buytime desc ,id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
	}
	public function indexfour(){

		$model = M('Numberfour');
		
		import('ORG.Util.Page');// 导入分页类
		
		
		
		$map['belongid'] = $_SESSION['login_user'];
		
		$count      = $model->where($map)->count();// 查询满足要求的总记录数
		
		$Page       = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		
		$show       = $Page->show();// 分页显示输出
		
		$list = $model->where($map)->order('status asc , buytime desc ,id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
	}
	
	
	
}