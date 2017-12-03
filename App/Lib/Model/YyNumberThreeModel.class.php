<?php
	class YyNumberThreeModel extends ViewModel{
		
		//protected $tableName = 'number'; 
		
		public $viewFields = array(
			'yy_numberthree' => array(
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
				'_table' => 'yy_numberthree',
				'_type' => 'LEFT',
			),
			'yy_proxy' => array(
				'username',
				'_table' => 'yy_proxy',
				'_on' => 'yy_numberthree.belongid = yy_proxy.id',
			),
		);
		
	}
?>