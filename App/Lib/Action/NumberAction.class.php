<?php
// 本类由系统自动生成，仅供测试用途
class NumberAction extends CommonAction {
	
	
    public function index(){
		//输出所有大区名称
		$area_name = M('setting') -> select();
		$this -> assign('area_name',$area_name);
		
		$model = D('YyNumber');
		
		//已卖总数
		$map_count['status'] = '1'; 
		$buy_count = $model -> where($map_count) ->count();
		$this -> assign('buy_count',$buy_count);
		
		if($_GET['checknotbuy'] == 'yes'){
			$this -> assign('checknotbuy','yes');
		}
		
		
		import('ORG.Util.Page');// 导入分页类
		
		
		if($_GET['username'] != null || $_GET['username'] != '' ){
			$map['username'] = array('like','%'.$_GET['username']."%");
			
		}
		if($_GET['area'] != null || $_GET['area'] != '' ){
			$map['area'] = array('like',$_GET['area']);
			
		}
		if($_GET['uid'] != null || $_GET['uid'] != '' ){
			$map['uid'] = array('like',$_GET['uid']);
			
		}
		if($_GET['number'] != null || $_GET['number'] != '' ){
			$map['number'] = array('like',$_GET['number']);
			
		}
		
		
		
		$count      = $model->where($map)->count();// 查询满足要求的总记录数
		$this -> assign('number_count',$count);
		$Page       = new Page($count,1000);// 实例化分页类 传入总记录数和每页显示的记录数
		
		$show       = $Page->show();// 分页显示输出
		
		$list = $model-> where($map)-> order('status asc,price desc,buytime asc') -> limit($Page->firstRow.','.$Page->listRows) -> select();
		//dump($model -> getLastSql());exit;
		
		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
	}
	
	public function addNumber(){
		$model = M('setting');
		$res = $model -> select();
		$this -> assign('res',$res);
		$this -> display();
	}
	public function submitNumber(){
		$model = M('number');
		
		$data['number'] = $_POST['number'];
		
		//检查是否重名
		$map['number'] = $_POST['number'];

		$is_name = $model -> where($map) -> find();

		if($is_name){
			$this -> error('此账号已存在');
		}
		
		//$data['pre'] = $_POST['pre'];
		//用前缀去编号
		$data['uid'] = $_POST['pre'].($this -> makeId($_POST['pre']));
		
		$data['password'] = $_POST['password'];
		$data['area'] = $_POST['area'];
		$data['content'] = $_POST['content'];
		$data['remark'] = $_POST['remark'];
		$data['price'] = $_POST['price'];
		$data['addtime'] = time();
		$res = $model -> add($data);
		$this -> updateSsr();
		if($res){
			$this -> success('添加成功','index');
		}else{
			$this -> error('添加失败，联系我');
		}
	}
	
	public function deleteNumber(){
		$model = M('number');
		if($_POST['id'] != null){
			$map['id'] = array('in',$_POST['id']);
			
		}
		if($_GET['id'] !=null || $_GET['id'] !='' ){
			$map['id'] = $_GET['id'];
		}
		
		$res = $model -> where($map) -> delete();
		
		if($res){
			$this -> success('删除成功','__URL__/index');
		}else{
			$this -> error('删除失败，联系我');
		}
	}
	public function upload(){
		$this -> display();
	}
	public function doupload(){
		$model = M('number');
		if (!empty($_FILES)) {
			import("ORG.Util.UploadFile");
			$config=array(
                'allowExts'=>array('xlsx','xls'),
                'savePath'=>'./Public/upload/',
                'saveRule'=>'time',
			);
			$upload = new UploadFile($config);
			
			if (!$upload->upload()) {
				$this->error($upload->getErrorMsg());
			} else {
				$info = $upload->getUploadFileInfo();
			}
			
			vendor("PHPExcel.PHPExcel");
			$file_name=$info[0]['savepath'].$info[0]['savename'];
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$objPHPExcel = $objReader->load($file_name,$encode='utf-8');
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow(); // 取得总行数
			$highestColumn = $sheet->getHighestColumn(); // 取得总列数
			
			//总共上传的个数
			$count_all = 0;
			//新增的个数
			$count_add = 0;
			//已被提取的个数
			$count_buy = 0;
			//重复的个数
			$count_repeat = 0;
			
			//简称数组
			$res_pre = M('makeid') -> field('pre') -> select();
			foreach($res_pre as $vo){
				$pre_list[] = $vo['pre'];
			}

			//检测大区简称是否存在系统中
			for($i=2;$i<=$highestRow;$i++){
				$pre_temp = (string)$objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
				if(!$pre_temp || $pre_temp == ''){
					continue;
				}
				
				if(!in_array($pre_temp,$pre_list)){
					$this -> error('系统中不存在Excel表中的前缀'.$pre_temp.'，请添加后再来');
				}
			}

			for($i=2;$i<=$highestRow;$i++)
			{
				
				//前缀C
				$data['pre'] = (string)$objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
				
				//大区D
				$data['area'] = (string)$objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
				
				//组合E
				$data['content'] = (string)$objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
				
				//备注F
				$data['remark'] = (string)$objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();
				
				//价格G
				$data['price'] = (string)$objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();
				
				//账号A
				$data['number'] = (string)$objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
				
				if(!$data['number'] || $data['number'] == ''){
					continue;
				}
				
				//密码B
				$data['password'] = (string)$objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
				
				
				$data['addtime'] = time();

				//根据组合算出价格 如果是0或者空就算
				if(!$data['price'] || $data['price'] == '' || $data['price'] == '0'){
					//把账号组合传入，算出价格
					$data['price'] = $this -> makePrice($data['content']);
				}
				
				//先判断之前有没有这个账号
				if($this -> isExist($data['number'])){
					//存在账号，去看下是否被卖 卖了的话 跳过
					if(!$this -> isBuy($data['number'])){
						//存在账号，直接覆盖
						$count_repeat = $count_repeat+1;
						$this -> saveTemp($data['number'],'repeat');
						$update_data['area'] = $data['area'];
						$update_data['content'] = $data['content'];
						$update_data['remark'] = $data['remark'];
						$update_data['price'] = $data['price'];
						$update_data['password'] = $data['password'];

						
						$update_data['time'] = time();
						$update_map['number'] = $data['number'];
						
						$update_data['ssr_number'] = $this -> updateSsr($update_data['content']);
						
						$model -> where($update_map) -> save($update_data);
						
					}else{
						//被卖了 存到临时表
						$this -> saveTemp($data['number'],'buy');
						$count_buy = $count_buy+1;
					}
					
				}else{
					$count_add = $count_add+1;
					//不存在，就去编号，插入数据
					$data['uid'] = $data['pre'].($this -> makeId($data['pre']));
					$data['ssr_number'] = $this -> updateSsr($data['content']);
					$model -> add($data);
					
				}
				$count_all = $count_all+1;
				
				
			}
			
			$count = array('all'=>$count_all,'buy'=>$count_buy,'add'=>$count_add,'repeat'=>$count_repeat);

			$this->success('导入成功！',U('addRes',array('count'=>$count)));
		}else
		{
			$this->error("请选择上传的文件");
		}
		
	}
	

	
	//添加价格
	//参数 账号的组合
	public function makePrice($str){
		//判断是否有SSR 
		if(strstr($str,'SSR')){
			$mark = 1;
		}else{
			$mark = 0;
		}

		//先把名字拆开，然后遍历这个数组，看多少钱
		$str = explode('+',$str);
		$model = M('price');
		$price = 0;
		//如果mark 则情况为 有SSR时 
		if($mark == '1'){
			$map['area'] = array('like','有SSR时');
			foreach($str as $vo){
				//如果这个选项是ssr
				if(strstr($vo,'SSR')){
					$map['name'] = $vo;
				}elseif(strstr($vo,'[SR]')){
					//如果这个选项是[SR]
					$map['name'] = 'SR';
				}else{
					$map['name'] = 'R';
				}
				
				//得到价格
				$data = $model -> field('price') -> where($map) ->   find();
				$price=$price+$data['price'];
			}
		}else{
			//情况为 无SSR时
			$map['area'] = array('like','无SSR时');
			foreach($str as $vo){
				//如果这个选项是sr
				if(strstr($vo,'[SR]')){
					$map['name'] = 'SR';
					//得到价格
					$data = $model -> field('price') -> where($map) ->   find();
					$price=$price+$data['price'];
				}
				
				
			}
		}
		
		return $price;
	
	}
	
	//判断是否存在
	public function isExist($number){
		$model = D('YyNumber');
		
		$map['number'] = $number;
		
		$res = $model -> where($map) -> find();
		
		if($res){
			//存在
			return true;
		}else{
			//不存在
			return false;
		}
	}
	
	//编号
	public function makeId($pre){
		$model = M('makeid');
		
		$map['pre'] = $pre;
		//先找目前多少个了
		$count_pre = $model -> where($map) -> find();
		
		//编号就是 这个个数 加一
		$res_pre = intval($count_pre['count']) + 1;
		
		//再把这个值 回存到编号表里
		$data['count'] = $res_pre;
		$model -> where($map) -> save($data);
		//生成六位编号
		$res = sprintf("%06d", $res_pre);
		
		return $res;
		
			
		
	}
	
	//判断账号是否被卖
	public function isBuy($number){
		$model = D('YyNumber');
		
		$map['number'] = $number;
		
		$map['status'] = '1';
		
		$res = $model -> where($map) -> find();
		
		if($res){
			//被卖了
			return true;
		}else{
			//没有被卖
			return false;
		}
		
		
	}
	
	function saveTemp($number,$type){
		$model = M('temp');
		$data['number'] = $number;
		$data['addtime'] = time();
		
		if($type == 'buy'){
			$data['type'] = 'buy';
		}else{
			$data['type'] = 'repeat';
		}
		
		$model -> add($data);
	}
	
	public function addRes(){
		
		$model = M('temp');
		$res = $model -> select();
		$this -> assign('res',$res);
		$this -> assign('count',$_GET['count']);
		$this -> display();
	}
	
	public function deleteTemp(){
		$model = M('temp');
		
		$model -> query($sql = 'TRUNCATE table `yy_temp`');
		
		
		$this -> success('OK','__URL__/index');
		
	}
	
	public function stopNumber(){
		$model = M('number');
		$res = $model -> field('stop') -> find();
		
		$this -> assign('res',$res['stop']);
		$this -> display();
	}
	
	public function submitStop(){
		$model = M('number');
		if($_GET['stop'] == 'yes'){
			//把所有状态改为stop
			$data['stop'] = 'stop';
			$res = $model -> where('id >= 1') -> save($data);
		}else{
			$data['stop'] = '';
			$res = $model -> where('id >= 1') -> save($data);
		}
		//dump($model -> getLastSql());exit;
		
			if($_GET['stop'] == 'yes'){
				$this -> success('已全部暂停','__URL__/index');
			}else{
				$this -> success('已全部恢复','__URL__/index');
			}
			

	}
	
	public function editNumber(){
		//输出所有大区名称
		$area_name = M('setting') -> select();
		$this -> assign('res2',$area_name);
		
		$model = M('number');
		$map['id'] = $_GET['id'];
		$res = $model -> where($map) -> find();
		$this -> assign('res',$res);
		$this -> display();
	}
	
	public function submitEdit(){
		$model = M('number');
		$map['id'] = $_POST['id'];
		$data['number'] = $_POST['number'];
		$data['pre'] = $_POST['pre'];
		$data['area'] = $_POST['area'];
		$data['content'] = $_POST['content'];
		$data['remark'] = $_POST['remark'];
		$data['price'] = $_POST['price'];
		$data['addtime'] = time();
		
		$res = $model -> where($map) -> save($data);
		if($res){
			$this -> success('修改成功','index');
		}else{
			$this -> success('修改失败');
		}
	}
	
	public function updateSsr($str){
		$count = substr_count($str,'SSR');
		return $count;
		
		/*
		$model = M('number');
		$list = $model -> select();
		foreach($list as $vo){
			$temp = $vo['content'];
			$count = substr_count($temp,'SSR');
			$map['id'] = $vo['id'];
			$data['ssr_number'] = $count;
			$model -> where($map) -> save($data);
		}
		*/
		
		
	}
	
	//处理历史数据 加SSR个数
	public function dododo(){
		$model = M('number');
		$list = $model -> select();
		foreach($list as $vo){
			$temp = $vo['content'];
			$count = substr_count($temp,'SSR');
			$map['id'] = $vo['id'];
			$data['ssr_number'] = $count;
			$model -> where($map) -> save($data);
		}
		echo "success";
	}
	
	//定价
	public function price(){
		$model = M('price');
		$res = $model -> select();
		$this -> assign('res',$res);
		$this -> display();
	}
	
	//增加定价
	public function addPrice(){
		$model = M('searchlist');
		$map['type'] = array('like','check_ssr');
		$res = $model -> where($map) -> order('name') -> select();
		$this -> assign('res',$res);
		$this -> display();
	}
	
	public function submitPrice(){
		$model = M('price');
		$data['name'] = $_POST['name'];
		$data['area'] = $_POST['area'];
		$data['price'] = $_POST['price'];
		
		$res = $model -> add($data);
		
		if($res){
			$this -> success('添加成功','price');
		}else{
			$this -> success('添加失败');
		}
		
		
	}
	
	public function deletePrice(){
		$model = M('price');
		$map['id'] = $_GET['id'];
		$res = $model -> where($map) -> delete();
		
		if($res){
			$this -> success('删除成功','__URL__/price');
		}else{
			$this -> success('删除失败');
		}
	}
	
	public function editPrice(){
		//输出所有名称
		$model = M('searchlist');
		$map['type'] = array('like','check_ssr');
		$res = $model -> where($map)-> order('name') -> select();
		$this -> assign('res',$res);
		
		$model_price = M('price');
		$map_price['id'] = $_GET['id'];
		$res_price = $model_price -> where($map_price) -> find();
		$this -> assign('res_price',$res_price);
		$this -> display();
	}
	
	public function submitPriceEdit(){
		$model = M('price');
		$map['id'] = $_POST['id'];
		$data['name'] = $_POST['name'];

		$data['area'] = $_POST['area'];

		$data['price'] = $_POST['price'];

		
		$res = $model -> where($map) -> save($data);
		if($res){
			$this -> success('修改成功','price');
		}else{
			$this -> success('修改失败');
		}
	}
	
	
	
	
	public function editOtherPrice(){
		$model_price = M('price');
		$map_price['id'] = $_GET['id'];
		$res_price = $model_price -> where($map_price) -> find();
		$this -> assign('res_price',$res_price);
		$this -> display();
	}
	
	public function submitOtherPrice(){
		$model = M('price');
		$map['id'] = $_POST['id'];
		$data['price'] = $_POST['price'];

		
		$res = $model -> where($map) -> save($data);
		if($res){
			$this -> success('修改成功','price');
		}else{
			$this -> success('修改失败');
		}
	}
	

}