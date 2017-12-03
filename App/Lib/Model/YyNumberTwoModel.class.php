<?php
	class YyNumberTwoModel extends ViewModel{
		
		//protected $tableName = 'number'; 
		
		public $viewFields = array(
			'yy_numbertwo' => array(
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
				'_table' => 'yy_numbertwo',
				'_type' => 'LEFT',
			),
			'yy_proxy' => array(
				'username',
				'_table' => 'yy_proxy',
				'_on' => 'yy_numbertwo.belongid = yy_proxy.id',
			),
		);
		
	}
?>