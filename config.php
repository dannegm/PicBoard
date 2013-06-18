<?php

	if ( $_SERVER["SERVER_NAME"] == "dannegm" ){
		define("domain", $_SERVER['SERVER_NAME'] . "/s");
		define("db_server", "localhost");
		define("db_user", "root");
		define("db_password", "");
		define("db_bdata", "picboard");
	}else{
		define("domain", $_SERVER['SERVER_NAME']);
		define("db_server", "localhost");
		define("db_user", "root");
		define("db_password", "A3dS4fD5g");
		define("db_bdata", "picboard");
	}

	//Base de datos
	define("tb_pics", "pics");
	define("tb_users", "users");
	define("tb_comments", "comments");

?>