<?php
// 本类由系统自动生成，仅供测试用途
class DlInfoAction extends DlCommonAction {
    public function index(){
		
		$model = M('proxy');
		$model_news = M('news');
		
		$map['username'] = $_SESSION['login_user'];
		
		$res = $model -> where($map) -> find();
		
		//提取记录中去找一共多少
		$res_use  = M('takerecord') -> where($map) -> sum('point');
		$this -> assign('pointuse',$res_use);
		
		$this -> assign('res',$res);
		
		$res_news = $model_news -> order('ishead desc,publishtime desc') -> select();
		$this -> assign('res_news',$res_news);
		$this -> display();
	}
	
	public function priceRecord(){
		$model = M('record');
		$map['username'] = $_SESSION['login_user'];
		$res = $model -> where($map) -> order('updatetime desc') -> select();
		
		$this -> assign('res',$res);
		$this -> display();
	}
	
	public function newsInfo(){
		$model = M('news');
		$map['id'] = $_GET['id'];
		$res = $model -> where($map) -> find();
		$this -> assign('res',$res);
		$this -> display();
	}
	
}