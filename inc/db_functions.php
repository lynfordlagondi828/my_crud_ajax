<?php
/*
*Author: Lynford A. Lagondi
*/
//Database Functions
class db_functions{

	//variable
	private $conn;

	//Constructor
	function __construct(){

		require_once 'inc/db_config.php';
		$database = new db_config();
		$this->conn = $database->db_connect();
	}

	//Save Comments
	public function saveComments($name,$comment){

		$sql = "INSERT INTO comments(name,comment)VALUES(?,?)";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(array($name,$comment));
		$result = $stmt->fetch();
		return $result;
	}	


	//display Comments
	public function getAllComments($start,$limit){

		$sql = "SELECT * FROM  comments LIMIT $start,$limit";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(array($start,$limit));
		$result = $stmt->fetchAll();
		return $result;
	}

	//Delete row
	public function delete_row($rowID){

		$sql = "DELETE FROM comments WHERE id = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(array($rowID));
		$result = $stmt->fetch();
		return $result;
	}

	//get single row
	public function getSingleRow($rowID){

		$sql = "SELECT * FROM comments WHERE id = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(array($rowID));
		$result = $stmt->fetch();
		return $result;
	}

	//update
	public function updateComments($rowID,$name,$comment){

		$sql = "UPDATE comments SET name = ?, comment = ? WHERE id = ?";
		$stmt= $this->conn->prepare($sql);
		$stmt->execute(array($name,$comment,$rowID));
		$result = $stmt->fetch();
		return $result;
	}
}
?>