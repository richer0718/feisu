<?php
// 本类由系统自动生成，仅供测试用途
class RechargeAction extends CommonAction {
    public function index(){
		$model = D('YyProxy');
		
		if($_GET['username'] != null || $_GET['username'] != '' ){
			$map['username'] = array('like',$_GET['username']);
			
		}
		
		import('ORG.Util.Page');// 导入分页类
		$count      = $model->where($map)->count();// 查询满足要求的总记录数
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $model->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
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
		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
	}
	public function recharge(){
		$model = D('YyProxy');
		$id = $_GET['id'];
		//根据他的id 把他的折扣带出来
		$map['id'] = $_GET['id'];
		$res = $model -> where($map) -> find();
		$this -> assign('res',$res);
		
		$this -> assign('username',$_GET['username']);
		$this -> assign('id',$id);
		$this -> display();
		
	}
	
	public function submitRecharge(){
		$id = $_POST['id'];
		$username = $_POST['username'];
		
		//代理模型
		$model_proxy = D('YyProxy');
		//充值记录模型
		$model_recharge = D('YyRecharge');
		
		$map['id'] = $id;
		//跟新代理的点数
		//先取到他的点数
		$point_pre = $model_proxy -> field('point') -> where($map) -> find();
		//这是他充值后的点数
		$data['point'] = floatval($point_pre['point']) + floatval($_POST['point']);
		$data['cut'] = $_POST['cut'];
		$update_res  =  $model_proxy -> where($map) -> save($data);
		
		$data_record['point'] = $_POST['point'];
		$data_record['username'] = $username;
		$data_record['updatetime'] = time();
		$data_record['remark'] = $_POST['remark'];
		
		$insert_res = $model_recharge -> add($data_record);
		
		if($update_res && $insert_res){
			$this -> success('充值成功','index');
		}else{
			$this -> error('报错，联系我');
		}
		
		
		
	}
	
	//充值记录
	public function record(){
		$model = D('YyRecharge');
		$model_proxy = M('proxy');
		
		$this -> assign('select_date',$select_date);
		
		
		if($_GET['username'] != null || $_GET['username'] != '' ){
			$map['username'] = array('like',$_GET['username']);
			
		}
		
		if( ($_GET['lefttime'] != null || $_GET['lefttime'] != '') && ($_GET['righttime'] != null || $_GET['righttime'] != '' )){
			
			$map['updatetime'] = array(array('EGT',strtotime($_GET['lefttime'])),array('ELT',strtotime($_GET['righttime'])) );
			
		}
		//总充值量
		$sum_point = $model -> where($map) -> sum('point');
		$this -> assign('sum_point',$sum_point);
		
		import('ORG.Util.Page');// 导入分页类
		$count      = $model->where($map)->count();// 查询满足要求的总记录数
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
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
}