<?php
	class YyNumberModel extends ViewModel{
		
		//protected $tableName = 'number'; 
		
		public $viewFields = array(
			'yy_number' => array(
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
				'_table' => 'yy_number',
				'_type' => 'LEFT',
			),
			'yy_proxy' => array(
				'username',
				'_table' => 'yy_proxy',
				'_on' => 'yy_number.belongid = yy_proxy.id',
			),
		);
		
	}
?>