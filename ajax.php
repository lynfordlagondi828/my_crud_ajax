<?php
require_once 'inc/db_functions.php';
$database = new db_functions();
	
	/**
	* POST method using key
	*/
	if (isset($_POST["key"])) {
		
		//Save POST Request
		if ($_POST["key"] == "addNew") {
			
			$name = trim($_POST["name"]);
			$comment = trim($_POST["comment"]);

			$database->saveComments($name,$comment);
			exit("success");

		}

		//display POST request
		if ($_POST["key"] == "list_of_comment") {
			
			$start = trim($_POST["start"]);
			$limit = trim($_POST["limit"]);

			$result = $database->getAllComments($start,$limit);

			if (count($result)>0) {
				
				$response = "";

				foreach ($result as $key) {
					
					$response .= '
					<tr>
						<td>'.$key['name'].'</td>
						<td>'.$key['comment'].'</td>
						<td>'.$key['created_at'].'</td>
						<td>
							<input type="button" onClick="ViewOreditRow('.$key['id'].', \'edit\')" value="Edit" class="btn btn-primary">
							<input class="btn btn-danger" type="button" onClick="deleteRow('.$key['id'].')" value="Delete">

							<input type="button" onClick="ViewOreditRow('.$key['id'].', \'view\')" value="View" class="btn btn-success">
						</td>
					</tr>
					';
				}
					exit($response);
			} else {

				exit("reachedMax");
			}
		}

		//Delete row
		if ($_POST["key"] == "delete") {
			
			$rowID = trim($_POST["rowID"]);

			$database->delete_row($rowID);
			exit("success");

		}

		//get single row
		if ($_POST["key"] == "get_single_row") {
			
			$rowID = trim($_POST["rowID"]);
			$rows = $database->getSingleRow($rowID);

			$jsonArray = array(
				"name" => $rows["name"],
				"comment" => $rows["comment"]
			);
			exit(json_encode($jsonArray));
		}

		//Update
		if ($_POST["key"] == "updateRow") {
			
			$rowID = trim($_POST["rowID"]);
			$name = trim($_POST["name"]);
			$comment = trim($_POST["comment"]);

			$database->updateComments($rowID,$name,$comment);
			exit('success');
		}
	}
?>