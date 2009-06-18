<?php

	require_once "config.php";
	require_once "_libs/_class/db.php";
	require_once "_libs/_class/model.php";
	require_once "_libs/_class/controller.php";
	require_once "_libs/_class/loader.php";
	
	
	// analyse and build the request
	$params = array_values ( array_filter ( explode ( "/", $_SERVER['REQUEST_URI'] ) ) );
	if ( $params[0] == BASEFOLDER ) {
		unset ( $params[0] );
		$params = array_values ( $params );
	}
	$controller = isset ( $params[0] ) ? $params[0] : DEFAULT_CONTROLLER;
	$action = isset ( $params[1] ) ? $params[1] : DEFAULT_ACTION;
	if ( count( $params ) > 2 ) {
		$the_request = array();
		$get = array_slice ( $params, 2 );
		for ( $i = 0; $i < count ( $get ); $i++ ) {
			$the_request[$get[$i]] = isset ( $get[$i+1] ) ? $get[$i+1] : "";
			$i++;
		}
	}
	
	
	if ( file_exists ( BASEPATH . "system/controllers/$controller.php" ) ) {
		
		include BASEPATH . "system/controllers/$controller.php";
		$classname = end ( explode ( "/", $controller ) );
		
		if ( class_exists ( $classname ) ) {
			
			$ctrl = new $classname();

			if ( method_exists ( $ctrl, $action ) ) {
			
				$ctrl->$action();
			
			} else {
			
				echo "<b>Error:</b> controller method '$action' not found !<br>";
			
			}
				
		} else {
		
			echo "<b>Error:</b> controller class '$classname' not found !<br>";
		
		}
		
	} else {
	
		echo "<b>Error:</b> controller '$controller' file not found !<br>";
	
	}


?>