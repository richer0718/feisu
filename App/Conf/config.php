<?php
return array(
	//'配置项'=>'配置值'
	//数据库配置信息
        'DB_TYPE'   => 'mysql', // 数据库类型
        'DB_HOST'   => 'qdm150698112.my3w.com', // 服务器地址
        'DB_NAME'   => 'qdm150698112_db', // 数据库名
        'DB_USER'   => 'qdm150698112', // 用户名
        'DB_PWD'    => 'zyf123456', // 密码
        'DB_PORT'   => 3306, // 端口
		'URL_MODEL' => 0,
		'DB_PREFIX' => 'yy_',
		 'LOG_RECORD' => false,    // 关闭日志记录
		
		//开启语言包
		'LANG_SWITCH_ON'        => false,   // 默认关闭语言包功能
		'LANG_AUTO_DETECT'      => true,   // 自动侦测语言 开启多语言功能后有效
		'LANG_LIST'             => 'zh-cn', // 允许切换的语言列表 用逗号分隔
		'VAR_LANGUAGE'          => 'l',		// 默认语言切换变量
        //其他项目配置参数
        // ...
);
?>