<?php

class db
{
	private $_pdo;
	private $_statement;
	private $_result;
	private $_handlers;
	private $_model;
	

	public function registerModel ( $model ) 
	{
		$this->_model = $model;
	}
	
	public function connect ( $driver = "sqlite", $host = "", $dbname = "", $username = "", $password = "" )
	{
		switch ( $driver ) {
			case "mysql":
				$this->_pdo = new PDO ( "mysql:host=$host;dbname=$dbname", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
				break;
			case "sqlite":
			default:
				$this->_pdo = new PDO ( "sqlite:system/_libs/_database/$dbname" );
				
		}
	}
	
	
	public function query ( $sql, $data = array() )
	{
		$this->_statement = $this->_pdo->prepare ( $sql );
		$this->_statement->execute ( $data );
	}
	
	
	public function fetch ( $fetch_mode = PDO::FETCH_OBJ ) // PDO::FETCH_ASSOC ou PDO::FETCH_BOTH
	{
		$this->_result = $this->_statement->fetch ( $fetch_mode );
		$this->executeHandlers ( $this->_result );
		return $this->_result;
	}
	
	
	public function fetchAll ( $fetch_mode = PDO::FETCH_BOTH ) 
	{
		$this->_result = $this->_statement->fetchAll ( $fetch_mode );
		$this->executeHandlers ( $this->_result );
		return $this->_result;
	}
	
	
	public function count ()
	{
		return $this->_statement->columnCount();
	}
	
	
	public function lastid ()
	{
		return $this->_pod->lastInsertId(); 
	}

	

	public function addHandler ( $key, $name )
	{
		$this->_handlers[$key] = $name;
	}


	public function freeHandlers ()
	{
		$this->_handlers = array();
	}


	public function executeHandlers ( &$data )
	{
		if ( empty ( $this->_handlers ) ) return;
		if ( empty ( $data ) ) return;
		foreach ( $data as $k => $v ) {
			if ( is_object ( $v ) ) $this->executeHandlers ( $data->{$k} );
			if ( is_array ( $v ) ) $this->executeHandlers ( $data[$k] );
			if ( isset ( $this->_handlers[$k] ) ) {
				$function = $this->_handlers[$k];
				if ( is_object ( $data ) ) {
					$data->{$k} = $this->_model->$function($v);
				} else {
					$data[$k] = $this->_model->$function($v);
				}
			}
		}
	}


}



?>