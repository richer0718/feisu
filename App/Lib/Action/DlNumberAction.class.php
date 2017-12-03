<?php
// 本类由系统自动生成，仅供测试用途
class DlNumberAction extends DlCommonAction {
	
	
    public function index(){
		//输出所有大区名称
		$area_name = M('setting') -> select();
		$this -> assign('area_name',$area_name);
		
		$model = D('YyNumber');
		
		import('ORG.Util.Page');// 导入分页类
		
		
		if($_GET['uid'] != null || $_GET['uid'] != '' ){
			$map['uid'] = $_GET['uid'];
			
		}
		if($_GET['area'] != null || $_GET['area'] != '' ){
			$map['area'] = array('like',$_GET['area']);
			
		}
		$map['stop'] = '';
		$count      = $model->where($map)->count();// 查询满足要求的总记录数
		
		$Page       = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		
		$show       = $Page->show();// 分页显示输出
		
		$list = $model->where($map)->order('status asc , buytime asc ,id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
	}
	
	public function takeNumber(){
		//判断他有没有钱
		if(!$this -> checkMoney($_GET['id'])){
			$this -> error('您的余额不足，请充值！');
		}
		//取得代理的折扣 和总点数
		$map_daili['username'] = $_SESSION['login_user'];
		
		$daili = M('proxy') -> where($map_daili) -> find();
		$this -> assign('daili',$daili);
		
		
		$model = M('number');
		$map['id'] = $_GET['id'];
		$res = $model -> where($map) -> find();
		
		$rel_price = round($res['price'] * $daili['cut'],1);
		
		$this -> assign('rel_price',$rel_price);
		$this -> assign('res',$res);
		$this -> display();
	}
	public function submitNumber(){
		//在账号表中 把此条账号标注已购买
		$model_number = M('number');
		$update_data['buyprice'] = $_POST['rel_price'];
		$update_data['belongid'] = $_SESSION['login_user'];
		$update_data['status'] = '1';
		$update_data['buytime'] = time();
		$update_map['id'] = $_POST['id'];
		$res1 = $model_number -> where($update_map) -> save($update_data);
		
		//代理表中 让他的余额减少
		$model_proxy = M('proxy');
		$proxy_name['username'] = $_SESSION['login_user'];
		$proxy_data['point'] = $_POST['point'] - $_POST['rel_price'];
		$proxy_data['number'] = $_POST['number'] + 1;
		$res2 = $model_proxy -> where($proxy_name) -> save($proxy_data); 
		
		//提取记录更新
		$model_take = M('takerecord');
		$take_data['username'] = $_SESSION['login_user'];
		$take_data['updatetime'] = time();
		$take_data['point'] = $_POST['rel_price'];
		$take_data['buynumber'] = $_POST['uid'];
		$res3 = $model_take -> add($take_data);
		
		if($res2){
			$this -> success('购买成功','index');
		}else{
			$this -> error('请重新操作');
		}
		
	}
	
	public function checkMoney($id){
		$model = M('number');
		$map['id'] = $id;
		$res = $model -> where($map) -> find();
		
		$model_daili = M('proxy');
		$map_daili['username'] = $_SESSION['login_user'];
		$daili = $model_daili -> where($map_daili) -> find();
		
		if($res['price']*$daili['cut'] < $daili['point']){
			return true;
		}else{
			return false;
		}
		
		
		
	}
	
}