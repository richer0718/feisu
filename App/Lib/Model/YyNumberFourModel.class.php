<?php
	class YyNumberFourModel extends ViewModel{
		
		//protected $tableName = 'number'; 
		
		public $viewFields = array(
			'yy_numberfour' => array(
				'id',
				'uid',
				'area',
				'content',
				'price',
				'buyprice',
				'number',
				'addtime',
				'buytime',
				'status',
				'belongid',
				'password',
				'_table' => 'yy_numberfour',
				'_type' => 'LEFT',
			),
			'yy_proxy' => array(
				'username',
				'_table' => 'yy_proxy',
				'_on' => 'yy_numberfour.belongid = yy_proxy.id',
			),
		);
		
	}
?>