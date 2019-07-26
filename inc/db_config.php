<?php
/**
* Author: Lynford A. Lagondi
*/
//Configuration
class db_config{

	private $conn;

	//Constructor
	function __construct(){

	}

	//Connect
	function db_connect(){
		$this->conn = new PDO("mysql:host=localhost;dbname=ajax_crud","root","");
		return $this->conn;
	}
}
?>