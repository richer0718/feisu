<?php
// 本类由系统自动生成，仅供测试用途
class NumberFourAction extends CommonAction {
	
	
    public function index(){
		//输出所有大区名称
		$area_name = M('settingfour') -> select();
		
		$this -> assign('area_name',$area_name);
		
		$model = D('YyNumberFour');
		
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
		
		$list = $model-> where($map)-> order('status asc,buytime asc') -> limit($Page->firstRow.','.$Page->listRows) -> select();

		
		$this->assign('res',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> display();
	}
	
	public function addNumber(){
		$model = M('settingfour');
		$res = $model -> select();
		$this -> assign('res',$res);
		$this -> display();
	}
	public function submitNumber(){
		
		$model = M('numberfour');
		
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
		$data['4_number'] = $this -> countFourStar($_POST['content']);
		$data['5_number'] = $this -> countFiveStar($_POST['content']);
		$res = $model -> add($data);
		
		if($res){
			$this -> success('添加成功','index');
		}else{
			$this -> error('添加失败，联系我');
		}
	}
	
	public function deleteNumber(){
		$model = M('numberfour');
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
	
	public function doUploadNumber(){
	    //header("Content-type:text/html;charset=utf-8");
	    $_SESSION['login_mark'] = md5('boss');
	    //dump(11);exit;
	    $model = M('numberfour');
	    //简称数组
		$res_pre = M('makeidfour') -> field('pre') -> select();
		foreach($res_pre as $vo){
			$pre_list[] = $vo['pre'];
		}
        //前缀
        $pre_temp = $_GET['pre'];
		if(!in_array($pre_temp,$pre_list)){
			echo '系统中不存你传的前缀'.$pre_temp.'，请添加后再来';exit;
		}else{
		    //前缀C
				$data['pre'] = $_GET['pre'];
				
				//大区D
				$data['area'] = $_GET['area'];
				
				//组合E
				$data['content'] = $_GET['content'];
				
				//备注F
				$data['remark'] = $_GET['remark'];
				
				//价格G
				$data['price'] = $_GET['price'];
				
				//账号A
				$data['number'] = $_GET['number'];
				
				//密码B
				$data['password'] = $_GET['password'];
				
				
				$data['addtime'] = time();

				//根据组合算出价格 如果是0或者空就算
				
				if(!$data['price'] || $data['price'] == '' || $data['price'] == '0'){
					//把账号组合传入，算出价格和羁绊
					$jiban_price = $this ->  makePrice($data['content']);
					
					$data['price'] = $jiban_price[1];
					$data['jiban'] = $jiban_price[0];
					
					
				}
				
				if(floatval($data['price']) < 1 && floatval($data['price']) > 0 ){
					//把账号组合传入，算出价格和羁绊
					$jiban_price = $this ->  makePrice($data['content']);
					
					$data['price'] = floatval($data['price'])*$jiban_price[1];
					$data['jiban'] = $jiban_price[0];
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
						$update_data['jiban'] = $data['jiban'];
						
						
						$update_data['password'] = $data['password'];
						
						
						
						/*
						//把账号组合传入，算出价格和羁绊
						$jiban_price = $this ->  makePrice($data['content']);
						
						
						if(floatval($update_data['price']) < 1){
							//把账号组合传入，算出价格和羁绊
							
							$update_data['price'] = floatval($update_data['price'])*$jiban_price[1];
						}
						
						
						$update_data['jiban'] = $jiban_price[0];
						*/
					
						
						$update_data['time'] = time();
						$update_map['number'] = $data['number'];
						

						
						
						//$update_data['ssr_number'] = $this -> updateSsr($update_data['content']);
						
						$model -> where($update_map) -> save($update_data);
						
					}else{
						//被卖了 存到临时表
						//$this -> saveTemp($data['number'],'buy');
						//$count_buy = $count_buy+1;
					}
					
				}else{
					$count_add = $count_add+1;
					//不存在，就去编号，插入数据
					$data['uid'] = $data['pre'].($this -> makeId($data['pre']));
					$model -> add($data);
					echo 'success';
				}
				//$count_all = $count_all+1;
		}
		
		
	}
	
	
	
	public function doupload(){
		$model = M('numberfour');
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
			$res_pre = M('makeidfour') -> field('pre') -> select();
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
				$data['jiban'] = '';
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
					//把账号组合传入，算出价格和羁绊
					$jiban_price = $this ->  makePrice($data['content']);
					
					$data['price'] = $jiban_price[1];
					$data['jiban'] = $jiban_price[0];
					
					
				}
				
				if(floatval($data['price']) < 1 && floatval($data['price']) > 0 ){
					//把账号组合传入，算出价格和羁绊
					$jiban_price = $this ->  makePrice($data['content']);
					
					$data['price'] = floatval($data['price'])*$jiban_price[1];
					$data['jiban'] = $jiban_price[0];
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
						$update_data['jiban'] = $data['jiban'];
						
						
						$update_data['password'] = $data['password'];
						
						
						
						/*
						//把账号组合传入，算出价格和羁绊
						$jiban_price = $this ->  makePrice($data['content']);
						
						
						if(floatval($update_data['price']) < 1){
							//把账号组合传入，算出价格和羁绊
							
							$update_data['price'] = floatval($update_data['price'])*$jiban_price[1];
						}
						
						
						$update_data['jiban'] = $jiban_price[0];
						*/
					
						
						$update_data['time'] = time();
						$update_map['number'] = $data['number'];
						

						
						
						//$update_data['ssr_number'] = $this -> updateSsr($update_data['content']);
						
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
		$model_hero = M('hero');
		$model_jiban = M('jiban');
		//$str = "【蜀10】祝融+【吴12】孙权+【吴10】周泰+【吴10】大乔+【吴10】小乔";
		//先把名字拆开，
		$str = explode('+',$str);
		
		

		//遍历这些英雄，看他们属于哪些羁绊
		foreach($str as $vo){
			$map_hero['heroname'] = $vo;
			$res = $model_hero -> where($map_hero) -> select();
			
			if($res){
				//把belongjiban 抽出来
				foreach($res as $vol){
					$temp[] = $vol['belongjiban'];
					$new_price += intval($vol['price']);
				}
			}
			
			 
		}
		
		
		
		//得到可能属于的羁绊数组

		foreach($temp as $vo){
			$temp2[] = explode(',',$vo);
		}

		foreach($temp2 as $vo){
			foreach($vo as $vol){
				$temp3[] = $vol;
			}
		}

		$jiban_array = array_unique($temp3);

		
		//循环得到的jiban-array 看每个羁绊的所要求英雄，是否都在此数组里$str
		foreach($jiban_array as $vo){
			//dump($vo);
			//查每个羁绊的所要求英雄
			$map_belong['name'] = $vo;
			$res_belong = $model_jiban -> where($map_belong) -> find();
			//dump($res_belong['content']);
			//dump();exit;
			//如果 $str 里包含 $res_belong['content']
			if($this -> checkIsChild(explode('、',$res_belong['content']),$str)){
				//如果包含，则把这个羁绊的名字存起来
				$save_result_name[] = $res_belong['name'];
				$save_result_price[] = $res_belong['price'];
			}
			
		}
		$save_result_name = implode('、',$save_result_name);
		foreach($save_result_price as $vo){
			$new_price += intval($vo);
		}

		
		
		return array($save_result_name,$new_price);
		

	
	}
	
	//判断   左边数组  是否为   右边数组  的子集
	public function checkIsChild($child,$parent){
		$check = true;
		foreach($child as $vo){
			if(in_array($vo,$parent)){
				continue;
			}else{
				$check =false;
			}
		}
		return $check;
	}
	
	//判断是否存在
	public function isExist($number){
		$model = D('YyNumberFour');
		
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
		$model = M('makeidfour');
		
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
		$model = D('YyNumberFour');
		
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
		$model = M('tempfour');
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
		
		$model = M('tempfour');
		$res = $model -> select();
		$this -> assign('res',$res);
		$this -> assign('count',$_GET['count']);
		$this -> display();
	}
	
	public function deleteTemp(){
		$model = M('tempfour');
		
		$model -> query($sql = 'TRUNCATE table `yy_tempfour`');
		
		
		$this -> success('OK','__URL__/index');
		
	}
	
	public function stopNumber(){
		$model = M('numberfour');
		$res = $model -> field('stop') -> find();
		
		$this -> assign('res',$res['stop']);
		$this -> display();
	}
	
	public function submitStop(){
		$model = M('numberfour');
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
		$area_name = M('settingfour') -> select();
		$this -> assign('res2',$area_name);
		
		$model = M('numberfour');
		$map['id'] = $_GET['id'];
		$res = $model -> where($map) -> find();
		$this -> assign('res',$res);
		$this -> display();
	}
	
	public function submitEdit(){
		$model = M('numberfour');
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
	

	
	
	
	//定价
	public function price(){
		$model = M('pricefour');
		$res = $model -> select();
		$this -> assign('res',$res);
		$this -> display();
	}
	
	//增加定价
	public function addPrice(){
		$model = M('searchlistfour');
		$map['type'] = array('like','check_ssr');
		$res = $model -> where($map) -> order('name') -> select();
		$this -> assign('res',$res);
		$this -> display();
	}
	
	public function submitPrice(){
		$model = M('pricefour');
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
		$model = M('pricefour');
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
		$model = M('searchlistfour');
		$map['type'] = array('like','check_ssr');
		$res = $model -> where($map)-> order('name') -> select();
		$this -> assign('res',$res);
		
		$model_price = M('pricefour');
		$map_price['id'] = $_GET['id'];
		$res_price = $model_price -> where($map_price) -> find();
		$this -> assign('res_price',$res_price);
		$this -> display();
	}
	
	public function submitPriceEdit(){
		$model = M('pricefour');
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
		$model_price = M('pricefour');
		$map_price['id'] = $_GET['id'];
		$res_price = $model_price -> where($map_price) -> find();
		$this -> assign('res_price',$res_price);
		$this -> display();
	}
	
	public function submitOtherPrice(){
		$model = M('pricefour');
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