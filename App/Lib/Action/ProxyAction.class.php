<?php
// 本类由系统自动生成，仅供测试用途
class ProxyAction extends CommonAction {
    public function index(){
		$model = D('YyProxy');
		
		if($_GET['username'] != null || $_GET['username'] != '' ){
			$map['username'] = array('like',$_GET['username']);
			
		}
		
		//计算剩余总数
		$sum_point = $model -> sum('point');
		$this -> assign('sum_point',$sum_point);
		
		//计算消耗总数
		$sum_use = M('takerecord') -> sum('point');
		$this -> assign('sum_use',$sum_use);
		
		import('ORG.Util.Page');// 导入分页类
		$count      = $model->where($map)->count();// 查询满足要求的总记录数
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $model->where($map)->order('cut asc,addtime asc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $k => $vo){
			$count_temp = 0;
			//用代理username去 takerecord表查
			$map_temp['username'] = $vo['username'];
			$temp_res = M('takerecord') -> where($map_temp) -> select();
			if($temp_res){
				foreach($temp_res as $vol){
					$count_temp = $count_temp + $vol['point'];
				}
				$list[$k]['usepoint'] = $count_temp;
			}
		}
		$this -> assign('count',$count);
		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
	}
	public function addProxy(){
		$this -> display();
	}
	
	public function submitProxy(){
		$model_proxy = D('YyProxy');
		
		$model_recharge = D('YyRecharge');
		
		//检查是否重名
		$map['username'] = $_POST['username'];
		$is_name = $model_proxy -> where($map) -> find();
		if($is_name){
			$this -> error('此账号已存在');
		}
		
		
		
		$data['username'] = $_POST['username'];
		$data['password'] = md5($_POST['password']);
		$data['remark'] = $_POST['remark'];
		$data['cut'] = $_POST['cut'];
		$data['point'] = $_POST['point'];
		$data['addtime'] = time();
		$data['updatetime'] = time();
		$data['type'] = '1';  //用户类型  代理为1 boss
		
		M('admin') -> add($data);
		$res1 = $model_proxy -> add($data);
		$res2 = $model_recharge -> add($data);
		
		if($res1 && $res2){
			$this -> success('添加成功','index');
		}
		
		
	}
	
	public function outProxy(){
		$model = D('YyProxy');
		
		if($_GET['username'] != null || $_GET['username'] != '' ){
			$map['username'] = array('like',$_GET['username']);
			
		}
		
		import('ORG.Util.Page');// 导入分页类
		$count      = $model->where($map)->count();// 查询满足要求的总记录数
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $model->where($map)->order()->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
	}
	
	public function submitOutProxy(){
		$model = D('YyProxy');
		
		$map['id'] = $_GET['id'];
		
		if($_GET['isout'] == 'yes'){
			$data['status'] = '暂停';
		}else{
			$data['status'] = '0';
		}
		
		$res = $model -> where($map) -> save($data);
		
		if($res){
			if($_GET['isout'] == 'yes'){
				$this -> success('已将代理账号为：'.$_GET['username'].'暂停成功');
			}else{
				$this -> success('已将代理账号为：'.$_GET['username'].'恢复成功');
			}
		}else{
			$this -> error('联系我');
		}
	}
	
	//提取记录
	public function takeNumber(){
		$model = M('takerecord');
		$model_proxy = M('proxy');
		
		if($_GET['username'] != null || $_GET['username'] != '' ){
			$map['username'] = array('like',$_GET['username']);
			
		}
		
		import('ORG.Util.Page');// 导入分页类
		$count      = $model->where($map)->count();// 查询满足要求的总记录数
		$Page       = new Page($count,1000);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $model->where($map)->order('updatetime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $k => $vo){
			$map_proxy['username'] = $vo['username'];
			$list[$k]['info'] = $model_proxy -> where($map_proxy) -> find();
		}
		
		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
	}
	
	//修改备注
	public function changeInfo(){
		$model = M('proxy');
		$map['id'] = $_GET['id'];
		$res = $model -> where($map) -> find();
		$this -> assign('res',$res);
		$this -> display();
	}
	
	public function changePass(){
		$model = M('proxy');
		$map['id'] = $_GET['id'];
		$res = $model -> where($map) -> find();
		$this -> assign('res',$res);
		$this -> display();
	}
	public function submitPass(){
		$model = M('proxy');
		$map['id'] = $_POST['id'];
		$data['password'] = md5($_POST['password']);
		$res1 = $model -> where($map) -> save($data);

		$model_admin = M('admin');
		$map_admin['username'] = $_POST['username'];
		$data_admin['password'] = md5($_POST['password']);
		$res = $model_admin -> where($map_admin) -> save($data_admin);

		
		if($res){
			$this -> success('修改成功','index');
		}else{
			$this -> error('操作失败');
		}
	}
	
	public function submitChange(){
		$model = M('proxy');
		$map['id'] = $_POST['id'];
		$data['remark'] = $_POST['remark'];
		$res = $model -> where($map) -> save($data);
		if($res){
			$this -> success('修改成功','index');
		}else{
			$this -> error('操作失败');
		}
	}
}