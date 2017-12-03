<?php
// 本类由系统自动生成，仅供测试用途
class Index2Action extends Action {
	
	//公共函数，不接受权限检查，写法 array('index');
	public $public_functions = array();
	
	public function top(){
		if($_SESSION['username'] && $_SESSION['userid']){

			$this -> assign('username',$_SESSION['username']);
			
			$this -> assign('userid',$_SESSION['userid']);
		}
	}
	
    public function index(){
		$this -> top();
		
		$model = M('admininfo');
		
		$map['type'] = "产品介绍";
		
		$res = $model -> where($map) -> find();
		
		$this -> assign('content',$res['content']);
		
		$this -> display();
	}
	public function about(){
		$this -> top();
		
		$model = M('admininfo');
		
		$map['type'] = "使用教程";
		
		$res = $model -> where($map) -> find();
		
		$this -> assign('content',$res['content']);
		
		$this -> display();
	}
	public function services(){
		$this -> top();
		
		$model = M('admininfo');
		
		$map['type'] = "辅助下载";
		
		$res = $model -> where($map) -> find();
		
		$this -> assign('content',$res['content']);
		
		$this -> display();
	}
	public function price(){
		$this -> top();
		
		$model = M('admininfo');
		
		$map['type'] = "购买正版";
		
		$res = $model -> where($map) -> find();
		
		$this -> assign('content',$res['content']);
		
		$this -> display();
	}
	public function projects(){
		$this -> top();
		
		$model = M('admininfo');
		
		$map['type'] = "问题汇总";
		
		$res = $model -> where($map) -> find();
		
		$this -> assign('content',$res['content']);
		
		$this -> display();
	}
	public function reg(){
		$this -> display();
	}
	public function getResult(){
		$model = M('userinfo');
		
		if($_POST['username'] == '' || $_POST['password'] == '' || $_POST['repassword'] == '' || $_POST['mail'] == '' || $_POST['tel'] == ''){
			//$this -> assign('jumpUrl','reg');
			$this -> assign('waitSecond','2');
			$this -> error('请输入必填项');
		}
		
		//检查两次输入是否相同
		if($_POST['password'] != $_POST['repassword']){
			$this -> assign('waitSecond','2');
			$this -> error('两次输入密码不同');
		}
		//检查用户是否重复
		$map['username'] = $_POST['username'];
		
		$res = $model -> where($map) -> find();
		
		if($res){
			$this -> assign('waitSecond','2');
			$this -> error('用户名已存在');
		}else{
			$data['username'] = $_POST['username'];
			
			$data['password'] = $_POST['password'];
			
			$data['tel'] = $_POST['tel'];
			
			$data['mail'] = $_POST['mail'];
			
			$data['regtime'] = date('Y-m-d H:i');
			
			$result = $model -> add($data);
			
			if($result){
				$this -> assign('jumpUrl','login');
				$this -> assign('waitSecond','2');
				$this -> success('注册成功，请登录');
			}
		}
	}
	
	
	//登录
	public function login(){
		$this -> display();
	}
	public function checkLogin(){
		//dump($_POST);exit;
		if($_POST['username'] == '' || $_POST['password'] == '' ){
			$this -> assign('waitSecond','2');
			
			$this -> error('请输入用户名或者密码');
		}
		$model = M('userinfo');
		
		$map['username'] = $_POST['username'];
		
		$map['password'] = $_POST['password'];
		
		$res = $model -> where($map) -> find();
		
		if($res){
			$_SESSION['username'] = $res['username'];
			$_SESSION['userid'] = $res['id'];
			$this -> assign('jumpUrl','index');
			$this -> assign('waitSecond','2');
			$this -> success('登录成功！正在跳转');
			 
		}else{
			$this -> assign('waitSecond','2');
			$this -> error('请输入正确的用户名或者密码');
		}
		
	}
	public function userCenter(){
		$this -> top();
		$this -> check();
		$this -> display();
	}
	public function changePass(){
		$this -> top();
		$this -> check();
		$this -> display();
	}
	public function select(){
		
		$this -> top();
		
		$map['username'] = $_SESSION['username'];
		
		$model = M('2note');
		
		$res = $model -> where($map) -> order('number desc') -> find();
		
		$n = intval($res['number']);
		
		if($n == 0){
			$n = 1;
		}
		
		
		$this -> assign('number',$n);
		
		$this -> display();
	}
	
	public function setMysetting(){
		$this -> top();
		
		$model = M('2setting2');
		
		$res = $model -> order('line asc') -> select();
		
		foreach($res as $key => $value){
			if($value['type'] == '1'){
				$res[$key]['sname'] = explode('-',$value['sname']);
			}
			
		}
		
		$this -> assign('reslist',$res);
		
		$this -> display();

	}
	public function setMysetting2(){
			$this -> top();
	
			$model_set = M('2mysetting');
			
			$map['name'] = $_SESSION['username'];
			
			$res_set = $model_set -> where($map) -> find();
			
			$str_set = explode(',',$res_set['value']);

			$model = M('2setting2');
		
		$res = $model -> order('line asc') -> select();
		
		foreach($res as $key => $value){
			if($value['type'] == '1'){
				$res[$key]['sname'] = explode('-',$value['sname']);
			}
			//加默认配置
			$res[$key]['value'] = $str_set[$key];
			
		}
		
		$this -> assign('number',$_POST['num']);
		
		$this -> assign('reslist',$res);
		
		$this -> display();
	}
	public function getSetResult2(){
		$str = implode(',',$_POST);
		$model = M('2mysetting');
		$map['name'] = $_SESSION['username'];
		$data['value'] = $str;
		$res = $model -> where($map) -> save($data);
		if($res){
			$this -> success('修改成功',U('Index2/select'));
		}else{
			$this -> error('修改失败');
		}
		
	}
	public function getSetResult(){
		$str = implode(',',$_POST);
		$model = M('2mysetting');
		$data['name'] = $_SESSION['username'];
		$data['value'] = $str;
		$res = $model -> add($data);
		if($res){
			$this -> success('设置成功',U('Index2/select'));
		}else{
			$this -> error('设置失败');
		}
		
	}
	//修改密码
	public function checkResult(){

		if($_POST['password'] == '' || $_POST['newpassword'] == '' || $_POST['renewpassword'] == ''){
			//$this -> assign('jumpUrl','reg');
			$this -> assign('waitSecond','2');
			$this -> error('请输入必填项');
		}
		
		//检查两次输入是否相同
		if($_POST['newpassword'] != $_POST['renewpassword']){
			$this -> assign('waitSecond','2');
			$this -> error('两次输入密码不同');
		}
		$model = M('userinfo');
		
		$map['username'] = $_SESSION['username'];
		
		$map['id'] = $_SESSION['userid'];
		
		$map['password'] = $_POST['password'];
		
		$res = $model -> where($map) -> find();
		
		if($res){
			$data['password'] = $_POST['newpassword'];
			
			$result = $model -> where($map) -> save($data);
			
			if($result){
				$_SESSION['username'] = '';
				$_SESSION['userid'] = '';
				$this -> assign('jumpUrl','index');
				$this -> success('修改成功，请重新登录');
			}else{
				$this -> error('修改失败');
			}
		}else{
			$this -> error('原密码输入错误');
		}
	}
	
	public function logOut(){
		$_SESSION['username'] = '';
		$_SESSION['userid'] = '';
		$this -> assign('jumpUrl','index');
		$this -> assign('waitSecond','2');
		$this -> error('退出成功');
	}
	
	//检验是否登录
	public function check(){
		if(empty($_SESSION['username'])){
			$this -> assign('jumpUrl','login');
			$this -> assign('waitSecond','2');
			$this -> error('您没有登录，请登录');
		}
	}
	
	public function ourList(){
		$this -> top();
		$map['number'] = $_GET['num'];
		
		$map['username'] = $_SESSION['username'];
		
		$model = M('2note');
		
		$res = $model -> where($map) -> order('id asc') -> select();
		
		//有设置标志
		$model_set = M('2mysetting');
		
		$map_set['name'] = $_SESSION['username'];

		$res_set = $model_set -> where($map_set) -> order('id asc') -> find();
		
		if($res_set){
			$mark = "yes";
		}else{
			$mark = "no";
		}
		
		$this -> assign('mark',$mark);
		
		$this -> assign('number',$_GET['num']);
			
		$this -> assign('res',$res);
		
		$this -> display();
		
		
		
	}
	
	public function selectList(){
		
		$this -> top();
		$this -> check();
		$model = M('2setting2');
		
		//选择了默认设置
		if($_POST['select'] == 'on'){
			$model_set = M('2mysetting');
			
			$map['name'] = $_SESSION['username'];
			
			$res_set = $model_set -> where($map) -> find();
			
			$str_set = explode(',',$res_set['value']);
			
		}
		
		$res = $model -> order('line asc') -> select();
		
		foreach($res as $key => $value){
			if($value['type'] == '1'){
				$res[$key]['sname'] = explode('-',$value['sname']);
			}
			//加默认配置
			$res[$key]['value'] = $str_set[$key];
			
		}
		
		
		
		$this -> assign('number',$_POST['num']);
		
		$this -> assign('reslist',$res);
		
		$this -> display();
	}
	
	public function getListResult(){
		
		//总数
		$model = M('2setting2');
		
		$res = $model -> count();
		
		$sum = intval($_POST['sum'])-1;
		
		
		$list = array_slice($_POST,1);
		
		//全部数据
		$newlist = array_chunk($list,$res,false);
		
		//得到需要转换的array
		$model_array = M('buildarray');
		$map_array['name'] = 'sangshi';
		$array = $model_array -> field('array') -> where($map_array)->find();
		$array = $array['array'];
		$array = explode(',',$array);
		
		//第一个替换
		foreach($newlist as $key => $vo){
			//  16 32
			//vo[3] 0/0  13  28 
			if($vo[3]==''){
				$newlist[$key][3] = "0/0";
			}
			if($vo[17]==''){
				$newlist[$key][17] = "0";
			}
			if($vo[34]==''){
				$newlist[$key][34] = "0";
			}

			
			$eq = $vo[0];
			
			$newlist[$key][0] = $array[$eq];
		}
		
		
		//遍历newlist 除去没有账号密码的 废数据
		for($i=0; $i<$sum ; $i++){
			//取出来
			
			if($newlist[$i][1]=='' || $newlist[$i][2]==''){
				
				$this -> error("账号密码是必填");
			}
			$rowlist[] = implode(',',$newlist[$i]);
			
			
		}
		
		
		$model = M('2note');
		
		
		foreach($rowlist as $value){
			$data['username'] = $_SESSION['username'];
			$data['list'] = $value;
			$data['number'] = $_GET['num'];
			$test_data = explode(',',$data['list']);
			$size_test = sizeof($test_data);
			if($size_test == $res){
				$model -> add($data);
			}
			
			
		}
		$this->redirect('Index2/select');
		
	}
	
		public function deletelist(){
		$model = M('2note');
		
		$map['id'] = $_GET['id'];
		$map['username'] = $_SESSION['username'];
		
		$res = $model -> where($map) -> delete();
		
		if($res){
			$this -> success("删除成功");
		}else{
			$this -> error("删除失败");
		}
	}
	
	public function getCode(){
		//http://localhost/feifei/index.php/Index2/getCode/wzname/账号/wzpwe/设备/pass/密码/game/  WARZ/COK
		
		
		
		$model = M('2note');
		$model_user = M('userinfo');
		$map['username'] = $_GET['wzname'];
		
		$map['password'] = $_GET['pass'];
		
		$res_user = $model_user -> where($map) -> find();
		
		if($res_user){
			$map_note['number'] = $_GET['wzpwe'];
			
			$map_note['username'] = $_GET['wzname'];
		
			$res = $model -> where($map_note) ->order('id asc')-> select();
			
			if($res){
				foreach($res as $vo){
				$list[] = $vo['list'];
			}
			$arr = implode('|',$list);
			echo $arr;
			}
			
		}
		
		
	}
	
	public function changeAll(){
		$this -> top();
		$model = M('2note');
		
		$map['number'] = $_GET['num'];
		
		$map['username'] = $_SESSION['username'];
		
		$res = $model -> where($map) -> order('id asc') -> select();
		
		
		foreach($res as $vo){
			$str[] = $vo['list'];
		}

		$model_set = M('2setting2');
		
		$res_set = $model_set -> order('line asc') -> select();
		
		//得到需要转换的array
		$model_array = M('buildarray');
		$map_array['name'] = 'sangshi';
		$array = $model_array -> field('array') -> where($map_array)->find();
		$array = $array['array'];
		$array = explode(',',$array);
		
		foreach($res_set as $key => $value){
	
			if($value['type'] == '1'){
				$res_set[$key]['sname'] = explode('-',$value['sname']);
			}
			foreach($str as $k => $vol){
			
				$str_ex = explode(',',$vol);
				
				$res_set[$key]['value'][$k] = $str_ex[$key];
				if($key == 0){
					
					foreach($array as $kee => $value){
						if($value == $str_ex[$key]){
							$res_set[$key]['value'][$k] = array_search($value,$array);
							
						}
					}
					
				}
				
			}
				
				
		}

		$len = sizeof($str);
		
		$this -> assign('len',$len);
		
		$this -> assign('reslist',$res_set);
		
		$this -> display();
		
	}
	public function getChangeResult(){
		
		//得到需要转换的array
		$model_array = M('buildarray');
		$map_array['name'] = 'sangshi';
		$array = $model_array -> field('array') -> where($map_array)->find();
		$array = $array['array'];
		$array = explode(',',$array);
		

		//总数
		$model = M('2setting2');
		
		$res_count = $model -> count();
		
		
		$list = array_slice($_POST,1);
		//全部数据
		$newlist = array_chunk($list,$res_count,false);
		
		foreach ($newlist as $key => $vo){
			//vo[3] 0/0  13  28 
			if($vo[3]==''){
				$newlist[$key][3] = "0/0";
			}
			if($vo[17]==''){
				$newlist[$key][17] = "0";
			}
			if($vo[34]==''){
				$newlist[$key][34] = "0";
			}
			$find = $vo[0];
			$newlist[$key][0] = $array[$find];
		}


		foreach($newlist as $key => $vo){
			$newlist[$key] = implode(',',$vo);
		}
		array_shift($newlist);
		
		
		$model = M('2note');
		
		$map['number'] = $_GET['num'];
		
		$map['username'] = $_SESSION['username'];
		
		$res = $model -> where($map) -> order('id asc') -> select();

		foreach ($res as $k => $vol){
			$map ['id'] = $vol['id'];
			
			$data['list'] = $newlist[$k];
			
			$test_data = explode(',',$data['list']);
			
			$size_test = sizeof($test_data);
			
			if($size_test == $res_count){
				$model -> where($map) -> save($data);
			}
			
			
			
		}
		$this -> success("修改成功",U('Index2/select'));
		
	}
	
	
}