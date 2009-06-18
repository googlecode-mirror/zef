<?php

	define ( "BASEFOLDER", "zef" ); 
	define ( "BASEPATH", $_SERVER['DOCUMENT_ROOT'] . BASEFOLDER . "/" );
	define ( "BASEURL", "http://" . $_SERVER['HTTP_HOST'] . "/" . BASEFOLDER . "/" );
	define ( "HELPERSPATH", BASEPATH . "/system/_libs/_helpers/" );
	define ( "HELPERSURL", BASEURL . "/system/_libs/_helpers/" );
	
	define ( "ADMIN_PASSWD", "admin" );
	
	define ( "DEFAULT_CONTROLLER", "main" );
	define ( "DEFAULT_ACTION", "index" );
	
	define ( "MODEL_AUTOCONNECT", true );
	define ( "DB_TYPE", "mysql" ); // mysql or sqlite
	define ( "DB_HOST", "" );
	define ( "DB_LOGIN", "" );
	define ( "DB_PASSWORD", "" );
	define ( "DB_NAME", "" );
	
	
?>