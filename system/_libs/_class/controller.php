<?php

class controller {
	
	public $load;
	private $registered;
	
	public function controller()
	{
		$this->load = new loader ( $this );
	}
	
	public function register ( $name, $object )
	{
		$this->registered->{$name} = $object;
	}

	public function __get ( $object_name )
	{
		return $this->registered->$object_name;
	}

	public function redirect ( $url )
	{
		@header ( "Location: " . BASEURL . $url );
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=" . BASEURL . "$url\">"; 
		echo "<script type=\"text/javascript\">document.location=\"" . BASEURL . "$url\";</script>";
	}
	
	public function request ( $index, $default = false )
	{
		global $the_request;
		if ( count ( $the_request ) ) return isset ( $the_request[$index] ) ? $the_request[$index] : $default;
		return $default;
	}

	public function post ( $key, $default = false )
	{
		return isset ( $_POST[$key] ) && $_POST[$key] != "" ? $_POST[$key] : $default;
	}

}

?>