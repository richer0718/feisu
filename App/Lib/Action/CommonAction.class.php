<?php
Class CommonAction extends Action{
   Public function _initialize(){
       // 初始化的时候检查用户权限
       $this->checkLogin();
   }
   
   public function checkLogin(){
	   if($_SESSION['login_mark'] != md5('boss')){
			$this -> assign('jumpUrl',U('Check/index'));
			$this -> assign('waitSecond','2');
			$this -> error('您没有登录，请登录');
	   }
   }
 }
?>