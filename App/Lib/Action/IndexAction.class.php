<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    public function index(){
		//加载配置
		$model = M('searchlist');
		$map['type'] = 'check_ssr';
		$res_ssr = $model -> where($map) -> select();
		$this -> assign('res_ssr',$res_ssr);
		
		$map['type'] = 'check_sr';
		$res_sr = $model -> where($map) -> select();
		$this -> assign('res_sr',$res_sr);
		
		$map['type'] = 'check_r';
		$res_r = $model -> where($map) -> select();
		$this -> assign('res_r',$res_r);
		
		$map['type'] = 'check_area';
		$res_ssr = $model -> where($map) -> select();
		$this -> assign('res_area',$res_ssr);
		
		$this -> display();
	}
	
	public function fgo(){
		//加载配置
		$model = M('searchlistthree');
		$map['type'] = '5star';
		$res = $model -> where($map) -> select();
		
		$this -> assign('res_star',$res);
		
		$map['type'] = '4star';
		$res = $model -> where($map) -> select();
		
		$this -> assign('res_starf',$res);
		
		
		$map['type'] = '5starli';
		$res_li = $model -> where($map) -> select();
		$this -> assign('res_li',$res_li);
		
		$model_area = M('settingthree');
		$res_area = $model_area -> where($map1) -> select();
		$this -> assign('res_area',$res_area);
		
		
		$this -> display();
	}
	
	public function benghuai3(){
		//加载配置
		$model = M('searchlisttwo');
		$map['type'] = '女武神';
		$res = $model -> where($map) -> select();
		$this -> assign('res_star',$res);
		
		$map['type'] = '装备';
		$res_li = $model -> where($map) -> select();
		$this -> assign('res_li',$res_li);
		
		$model_area = M('settingtwo');
		$res_area = $model_area -> where($map1) -> select();
		$this -> assign('res_area',$res_area);
		
		
		$this -> display();
	}
	
	public function sanguo(){
		//加载配置
		$model = M('hero');
		$map1['heroname'] = array('like','【魏%');
		$res1 = $model -> where($map1) -> select();
		$this -> assign('res1',$res1);
		
		$map2['heroname'] = array('like','【蜀%');
		$res2 = $model -> where($map2) -> select();
		$this -> assign('res2',$res2);
		
		$map3['heroname'] = array('like','【吴%');
		$res3 = $model -> where($map3) -> select();
		$this -> assign('res3',$res3);
		
		$map4['heroname'] = array('like','【群%');
		$res4 = $model -> where($map4) -> select();
		$this -> assign('res4',$res4);
		
		$model_area = M('settingfour');
		//苹果官服
		$map1['name'] = array('like','%苹果官服%');
		$res_area = $model_area -> where($map1) -> select();
		$this -> assign('res_area',$res_area);
		//安卓官服
		
		$map2['name'] = array('like','%安卓官服%');
		$res_area2 = $model_area -> where($map2) -> select();
		$this -> assign('res_area2',$res_area2);
		
		$map3['name'] = array('like','%安卓渠道%');
		$res_area3 = $model_area -> where($map3) -> select();
		$this -> assign('res_area3',$res_area3);
		
		
		
		$this -> display();
	}
	
	public function serach(){
		
		

		if($_GET['check_ssr']){
			foreach($_GET['check_ssr'] as $vo){
				$arr_ssr.= " `content` like '%".$vo."%' and";
			}
			$arr_ssr = "(".(substr($arr_ssr,0,strlen($arr_ssr)-3)).") AND";
			
		}
		
		if($_GET['check_sr']){
			foreach($_GET['check_sr'] as $vo){
				$arr_sr.= " `content` like '%".$vo."%' and";
			}
			$arr_sr = "(".(substr($arr_sr,0,strlen($arr_sr)-3)).") AND";
			
		}
		
		if($_GET['check_r']){
			foreach($_GET['check_r'] as $vo){
				$arr_r.= " `content` like  '%".$vo."%' and";
			}
			$arr_r = "(".substr($arr_r,0,strlen($arr_r)-3).") AND";
			
		}
		
		
		if($_GET['check_ssr'] || $_GET['check_sr'] || $_GET['check_r']){
			
			$map_content = $arr_ssr.$arr_sr.$arr_r;
			
		}

		
		if($_GET['area']){
			
			$map_area = "`area` like '%".$_GET['area']."%' AND";
		}
		
		if($_GET['zuhe']){
			$map_zuhe = "AND `ssr_number` like ".$_GET['zuhe'];
		}

		$sql = "select * from `yy_number` where `status`=0 AND ".$map_content.$map_area." `stop` = ''".$map_zuhe;
		
		
		import('ORG.Util.Page');// 导入分页类
		
		$sql_count = "select count(*) as count from `yy_number` where `status`=0 AND ".$map_content.$map_area." `stop` = ''".$map_zuhe;
		$count      = M()->query($sql_count);// 查询满足要求的总记录数
		
		$Page       = new Page($count[0]['count'],50);// 实例化分页类 传入总记录数和每页显示的记录数
		
		$show       = $Page->show();// 分页显示输出
		
		
		$sql_list = "select * from `yy_number` where `status`=0 AND ".$map_content.$map_area." `stop` = '' ".$map_zuhe." order by `price` desc limit ".$Page->firstRow.','.$Page->listRows;
		
		$list = M() -> query($sql_list);

		$res = M() -> query($sql);
		foreach($list as $k => $vo){
			$list[$k]['content'] = $this -> changeColor($vo['content']);
			
		}

		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
		
	}
	
	//改变颜色 阴阳师
	public function changeColor($content){
		//加载配置
		$model = M('searchlist');
		$map['type'] = 'check_ssr';
		$res_ssr = $model -> where($map) -> select();
		foreach($res_ssr as $k => $vo){
			$ssr[$k] = $vo['name'];
		}

		
		$map['type'] = 'check_sr';
		$res_sr = $model -> where($map) -> select();
		foreach($res_sr as $k => $vo){
			$sr[$k] = $vo['name'];
		}
		
		$map['type'] = 'check_r';
		$res_r = $model -> where($map) -> select();
		foreach($res_r as $k => $vo){
			$r[$k] = $vo['name'];
		}
		
		$temp = explode('+',$content);
		
		foreach($temp as $k => $vo){
			if(in_array($vo,$r)){
				$return[$k] = "<a style='color:green;'>".$vo."</a>";
			}
			if(in_array($vo,$sr)){
				$return[$k] = "<a style='color:#7030A0;'>".$vo."</a>";
			}
			if(in_array($vo,$ssr)){
				$return[$k] = "<a style='color:red;'>".$vo."</a>";
			}
		}
		
		$return = implode('+',$return);
		return $return;
		
	}
	
	//改变颜色 命运
	public function changeColor2($content){

		
		$temp = explode('+',$content);

		foreach($temp as $k => $vo){

			if(strstr($vo,'【5星】')){
				$return[$k] = "<a style='color:red;'>".$vo."</a>";
			}
			
			if(strstr($vo,'【4星】')){
				$return[$k] = "<a style='color:green;'>".$vo."</a>";

			}
			if(strstr($vo,'[5星礼装]')){
				$return[$k] = "<a style='color:#7030A0;'>".$vo."</a>";
			}
			if(strstr($vo,'[4星礼装]')){
				$return[$k] = "<a style='color:blue;'>".$vo."</a>";
			}
		}

		$return = implode('+',$return);

		return $return;
		
	}
	
	//改变颜色 三国
	public function changeColor3($content){

		
		$temp = explode('+',$content);

		foreach($temp as $k => $vo){

			if(strstr($vo,'【吴12】孙权')||strstr($vo,'【吴12】太史慈') ||strstr($vo,'【吴12】陆逊') ||strstr($vo,'【魏12】夏侯惇')  ||strstr($vo,'【魏12】曹操')  ||strstr($vo,'【蜀12】刘备')  ||strstr($vo,'【蜀12】张飞')  ||strstr($vo,'【蜀12】马超')  ||strstr($vo,'【群12】貂蝉') ||strstr($vo,'【群12】贾诩')  ||strstr($vo,'【群12】董卓') ||strstr($vo,'【魏12】许褚')   ){
				                          
				$return[$k] = "<a style='color:red;'>".$vo."</a>";
			}else{
				$return[$k] = "<a style='color:blue;'>".$vo."</a>";
			}

		}

		$return = implode('+',$return);

		return $return;
		
	}
	
	//崩坏3
	public function searchtwo(){
		$model = M('numbertwo');
		if($_GET['star']){

			
			foreach($_GET['star'] as $vo){

				$arr_star[] = array('like',"%".$vo."%");
			}
			
			//获得英雄的条件   $arr_star
			

		}
		
		if($_GET['starli']){

			
			foreach($_GET['starli'] as $vo){

				$arr_li[] = array('like',"%".$vo."%");
			}
			
			//获得五星礼装的条件  $arr_li
		}
		
		if($_GET['star']  && $_GET['starli']){
			$map['content'] = array_merge($arr_star,$arr_li);
		}
		
		if($_GET['star']  && !$_GET['starli']){
			$map['content'] =  $arr_star;
		}
		
		if(!$_GET['star']  && $_GET['starli']){
			$map['content'] =  $arr_li;
		}
		
		if($_GET['area']){
			$map['area'] = $_GET['area'];
		}
		
		$map['status'] = '0';
		
		import('ORG.Util.Page');// 导入分页类
		
		$count      = $model->where($map)->count();// 查询满足要求的总记录数
		
		$Page       = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		
		$show       = $Page->show();// 分页显示输出
		
		$list = $model-> where($map) -> order('price desc') -> limit($Page->firstRow.','.$Page->listRows) -> select();

		/*
		foreach($list as $k => $vo){
			$list[$k]['content'] = $this -> changeColor2($vo['content']);
			
		}
		*/
		
		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
	}
	
	public function searchthree(){
		$model = M('numberthree');
		
		if($_GET['area']){
			$map['area'] = $_GET['area'];
		}
		
		if($_GET['star']){

			
			foreach($_GET['star'] as $vo){

				$arr_star[] = array('like',"%".$vo."%");
			}
			
			//获得英雄的条件   $arr_star
			

		}
		
		if($_GET['starli']){

			
			foreach($_GET['starli'] as $vo){

				$arr_li[] = array('like',"%".$vo."%");
			}
			
			//获得五星礼装的条件  $arr_li
		}
		
		if($_GET['star']  && $_GET['starli']){
			$map['content'] = array_merge($arr_star,$arr_li);
		}
		
		if($_GET['star']  && !$_GET['starli']){
			$map['content'] =  $arr_star;
		}
		
		if(!$_GET['star']  && $_GET['starli']){
			$map['content'] =  $arr_li;
		}
		
		
		
		
		if($_GET['5star']){
		    //是几 就大于几
			$map['5_number'] = array('EGT',$_GET['5star']);
		}
		
		if($_GET['4star']){
			$map['4_number'] = $_GET['4star'];
		}
		
		$map['status'] = '0';
		
		import('ORG.Util.Page');// 导入分页类
		
		$count      = $model->where($map)->count();// 查询满足要求的总记录数
		
		$Page       = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		
		$show       = $Page->show();// 分页显示输出
		
		$list = $model-> where($map) -> order('price desc') -> limit($Page->firstRow.','.$Page->listRows) -> select();

		foreach($list as $k => $vo){
			$list[$k]['content'] = $this -> changeColor2($vo['content']);
			
		}
		
		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
	}
	
	public function serachfour(){
		$model = M('numberfour');
		
		if($_GET['area']){
			if(strstr($_GET['area'],$_GET['type'])){
				$map['area'] =  $_GET['area'];
			}
		}
		
		if($_GET['area2']){
			if(strstr($_GET['area2'],$_GET['type'])){
				$map['area'] =  $_GET['area2'];
			}
		}
		
		if($_GET['area3']){
			if(strstr($_GET['area3'],$_GET['type'])){
				$map['area'] =  $_GET['area3'];
			}
		}
		
		if($_GET['heroname']){
			foreach($_GET['heroname'] as $vo){

				$arr_map[] = array('like',"%".$vo."%");
			}
			$map['content'] = $arr_map;
			
		}
		
		import('ORG.Util.Page');// 导入分页类
		
		$count      = $model->where($map)->count();// 查询满足要求的总记录数
		
		$Page       = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		
		$show       = $Page->show();// 分页显示输出
		
		$list = $model-> where($map) -> order('price desc') -> limit($Page->firstRow.','.$Page->listRows) -> select();
		foreach($list as $k => $vo){
			$list[$k]['content'] = $this -> changeColor3($vo['content']);
			
		}
		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
		
		
		
	}
	
}