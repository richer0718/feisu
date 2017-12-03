<?php
Class CheckAction extends Action{
   public function index(){
	   $this -> display();
   }
   
   public function doLogin(){
	   if($_SESSION['verify'] != md5($_POST['verify'])) {
		   $this->error('验证码错误！');
		}
		
		$map['username'] = $_POST['username'];
		$map['password'] = md5($_POST['password']);
		$map['type'] = $_POST['type'];
		
		$res = M('admin') -> where($map) -> find();
		if($res){
			if($_POST['type'] == 'boss'){
				$_SESSION['login_mark'] = md5('boss');
				$this -> success('登录成功',U('Admin/index'));
			}else{
				//去查查 这个账号是否被暂停 如果被暂停 则提示他
				if($this -> isOut($_POST['username'])){
					$this -> error('抱歉，您的账号被暂停了，请联系管理员');
				}
				
				$_SESSION['login_mark'] = md5('dailidaili');
				$_SESSION['login_user'] = $_POST['username'];
				$this -> success('登录成功',U('DlAdmin/index'));
			}
			
		}else{
			$this -> error('抱歉，登录失败');
		}
   }
   Public function verify(){
		import('ORG.Util.Image');
		Image::buildImageVerify();
	}
	
	public function loginOut(){
		$_SESSION['login_mark'] = null;
		$this -> success('退出成功','index');
	}
	
	public function isOut($username){
		$model = M('proxy');
		$map['username'] = $username;
		$map['status'] = '暂停';
		$res = $model -> where($map) -> find();
		return $res;
	}
 }
?>