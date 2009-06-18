<?php

class model {

	public $db;

	public function __construct()
	{
		$this->db = new db();
		$this->db->registerModel ( $this );
		if ( MODEL_AUTOCONNECT ) {
			$this->db->connect ( DB_TYPE, DB_HOST, DB_NAME, DB_LOGIN, DB_PASSWORD );
		}
	}

}

?>