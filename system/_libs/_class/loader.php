<?php

class loader {

	private $controller;

	public function __construct ( $controller )
	{
		$this->controller = $controller;
	}
	
	public function view ( $path, $data = array(), $return = false )
	{
		if ( file_exists ( BASEPATH . "system/views/$path.php" ) ) {
			extract ( $data );
			if ( $return ) {
				ob_start();
				include_once BASEPATH . "system/views/$path.php";
				return ob_get_clean();
			} else {
				include_once BASEPATH . "system/views/$path.php";
			}
		} else {
			echo "<b>Error:</b> view '$path' not found !<br>";
		}
	}

	public function model ( $path )
	{
		if ( file_exists ( BASEPATH . "system/models/$path.php" ) ) {
			
			include_once BASEPATH . "system/models/$path.php";
			$classname = end ( explode ( "/", $path ) );
		
			if ( class_exists ( $classname ) ) {
			
				$model = new $classname();
				$this->controller->register ( $classname, $model );
			
			} else {
				echo "<b>Error:</b> model class '$classname' not found !<br>";
			}
			
		} else {
			echo "<b>Error:</b> model '$path' not found !<br>";
		}
	}

	public function helper ( $path )
	{
		if ( file_exists ( BASEPATH . "system/_libs/_helpers/$path/$path.php" ) ) {
			include_once BASEPATH . "system/_libs/_helpers/$path/$path.php";
		} else {
			echo "<b>Error:</b> helper '$path' not found !<br>";
		}
	}

	
}


?>