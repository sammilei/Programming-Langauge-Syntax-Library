<?php
/**
 $servername = "localhost";
 $username = "xlei";
 $password = "P123fTlS9n*";
 $dbname = "xlei_test4";
 */
 
 /**
  * first tier: database
  */
// function getDataFromDB($page, $servername, $username, $password, $dbname) {
	// //Connect to a MySQL server
	// $mysqli = new mysqli($servername, $username, $password, $dbname);
	// if (mysqli_connect_errno()) {
		// printf("Connect failed: %s\n", mysqli_connect_error());
		//         exit;
	// }
	// //Create a prepared statement for pulling all entries from a page
	// if ($stmt = $mysqli -> prepare('SELECT title, entry FROM entries WHERE page=?')) {
		// //Create a multi-dimensional array to store
		// //the information from each entry
		// $entries = array();
		// ////Bind the passed parameter to the query, retrieve the data, and place
		// //it into the array $entries for later use
		// $stmt -> bind_param("s", $page);
		// //s -> type string
		// $stmt -> execute();
		// $stmt -> bind_result($title, $entry);
		// while ($stmt -> fetch()) {
			// $entries[] = array('title' => $title, 'entry' => $entry);
		// }
		// //Destroy the result set and free the memory used for it
		// $stmt -> close();
	// }
	// //Close the connection
	// $mysqli -> close();
// 
	// return $entries;
// }
// 
// function example($lan, $servername = "localhost", $username = "xlei", $password = "P123fTlS9n*", $dbname = "xlei_project") {
	// //Connect to a MySQL server
	// $mysqli = new mysqli($servername, $username, $password, $dbname);
	// if (mysqli_connect_errno()) {
		// printf("Connect failed: %s\n", mysqli_connect_error());
		//         exit;
	// }
	// //insert
	// $stmt = $mysqli -> prepare('INSERT INTO language VALUE (?)');
	// $stmt -> bind_param("s", $lan);
	// //s -> type string
	// $stmt -> execute();
// 
	// printf("%d Row inserted.\n", $stmt -> affected_rows);
// 
	// //Destroy the result set and free the memory used for it
	// $stmt -> close();
// 
	// //Close the connection
	// $mysqli -> close();
// }

function insertIntoTabel($data, $table, $servername = "localhost", $username = "xlei", $password = "P123fTlS9n*", $dbname = "xlei_project") {
	//Connect to a MySQL server
	$mysqli = new mysqli($servername, $username, $password, $dbname);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		        exit;
	}
	//insert
	$num_of_question_mark = count($data);
	if ($table == 'content' || $table == 'sub_content' || $table == 'record') {
		$num_of_question_mark++;
	}
	//for how many question mark we need
	$str_of_question_mark = "";
	//empty string
	for ($i = 0; $i < $num_of_question_mark; $i++) {
		if ($i != $num_of_question_mark - 1) {
			$str_of_question_mark .= "? ,";
		} else {
			$str_of_question_mark .= "?";
		}
	}
	$stmt = $mysqli -> prepare('INSERT INTO ' . $table . ' VALUES (' . $str_of_question_mark . ')');
	echo 'INSERT INTO ' . $table . ' VALUES (' . $str_of_question_mark . ')';

	//bind parameters to each table
	if ($table == 'language') {
		$stmt -> bind_param("s", $data);
	} else if ($table == 'content') {
		//get the max order
		$max = 0;
		if ($result = $mysqli -> query("SELECT MAX(order)FROM content")) {
			echo "i am here";
			while ($row = $result -> fetch_fields) {
				$max = $row -> order;
				echo "max is " . $max;
			}
			$max = $result + 1;
			$result -> close();
		}
		$stmt -> bind_param("si", $data, $max);
	} else if ($table == 'user') {
		$stmt -> bind_param("ss", $data[0], $data[1]);
	} else if ($table == 'sub_content') {
		//get the max order
		$max = 0;
		if ($result = $mysqli -> query("SELECT MAX(order)FROM sub_content")) {
			echo "i am here";
			while ($tem = $result -> fetch_object()) {
				$max = $tem -> order;
				echo "max is " . $max;
			}
			$max = $result + 1;
			$result -> close();
		}
		$stmt -> bind_param("sis", $data[0], $max, $data[1]);
	} else {
		//6 cols - record with auto increment primary key
		echo $data[0] . ' ' . $data[1] . ' ' . $data[2] . ' ' . $data[3] . ' ' . $data[4];
		$id = NULL;
		$stmt -> bind_param("isssss", $id, $data[0], $data[1], $data[2], $data[3], $data[4]);
	}

	//s -> type string
	$stmt -> execute();

	printf("%d Row inserted in " . $table . "\n", $stmt -> affected_rows);

	//Destroy the result set and free the memory used for it
	$stmt -> close();

	//Close the connection
	$mysqli -> close();
}

function searchALLFromDB($table, $servername = "localhost", $username = "xlei", $password = "P123fTlS9n*", $dbname = "xlei_project") {
	//Connect to a MySQL server
	$mysqli = new mysqli($servername, $username, $password, $dbname);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		        exit;
	}
	
	$sql = 'SELECT * FROM '.$table .' ORDER BY name';
	//echo $sql . '<br>';
	$what_to_display = [];
	if ($result = $mysqli -> query($sql)) {

		/* fetch associative array */
		while ($row = $result -> fetch_assoc()) {
			foreach ($row as $key => $value) {
				array_push($what_to_display, $value);
			}
		}
	}

	//Destroy the result set and free the memory used for it
	mysqli_free_result($result);
	mysqli_close($mysqli);
	return $what_to_display;
}

function searchSub_contentFromDB($content, $servername = "localhost", $username = "xlei", $password = "P123fTlS9n*", $dbname = "xlei_project") {
	//Connect to a MySQL server
	$mysqli = new mysqli($servername, $username, $password, $dbname);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		        exit;
	}
	
	$sql = 'SELECT name FROM sub_content WHERE content_name = \''.$content.'\' ORDER BY name';
	$what_to_display = [];
	if ($result = $mysqli -> query($sql)) {

		/* fetch associative array */
		while ($row = $result -> fetch_assoc()) {
			foreach ($row as $key => $value) {
				array_push($what_to_display, $value);
			}
		}
	}

	//Destroy the result set and free the memory used for it
	mysqli_free_result($result);
	mysqli_close($mysqli);
	return $what_to_display;
}

function searchRecordFromDB($lan, $content = NULL, $sub_content = NULL, $servername = "localhost", $username = "xlei", $password = "P123fTlS9n*", $dbname = "xlei_project") {
	//Connect to a MySQL server
	$mysqli = new mysqli($servername, $username, $password, $dbname);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		        exit;
	}
	$sql = NULL;

	/**
	 * build the condition statement
	 */
	$condition = '(';
	$field = 'r.Language_name = ';
	$count = count($lan);
	for ($i = 0; $i < $count; $i++) {
		$lan_wrap = "'" . $lan[$i] . "'";
		$condition .= $field . $lan_wrap;
		if ($i < $count - 1) {
			$condition .= " OR ";
		}
	}
	$condition .= ")";

	$col = "id, sub_content_name, Language_name, syntax, example ";
	//certain columnes for display later
	/**
	 * build the sql query
	 */
	if ($content != NULL) {
		//certain content with multi_language
		$sql = "SELECT " . $col . " 
		FROM record r 
		INNER JOIN sub_content sc 
			ON sc.name = r.sub_content_name
		WHERE sc.content_name = '" . $content . "' AND " . $condition . " ORDER BY sc.content_name, sc.name, r.Language_name";

	} else {
		//all content with multi_language
		$sql = "SELECT " . $col . " 
		FROM record r 
		INNER JOIN sub_content sc 
			ON sc.name = r.sub_content_name
		WHERE " . $condition . " ORDER BY sc.content_name, sc.name, r.Language_name";
	}
	
	if($lan == NULL){
		$sql = "SELECT " . $col . " 
		FROM record r 
		INNER JOIN sub_content sc 
			ON sc.name = r.sub_content_name
		WHERE sc.content_name = '".$content."' ORDER BY sc.name, r.Language_name";
	}

	//echo $sql . "<br>";
	$what_to_display = [];
	if ($result = $mysqli -> query($sql)) {

		/* fetch associative array */
		while ($row = $result -> fetch_assoc()) {
			foreach ($row as $key => $value) {
				//printf("%s -> %s <br>", $key, $value);
				array_push($what_to_display, $value);
			}
		}
	}

	//Destroy the result set and free the memory used for it
	mysqli_free_result($result);
	mysqli_close($mysqli);
	return $what_to_display;
}

function updateRecordFromDB($id, $syntax, $example, $servername = "localhost", $username = "xlei", $password = "P123fTlS9n*", $dbname = "xlei_project") {
	//Connect to a MySQL server
	$mysqli = new mysqli($servername, $username, $password, $dbname);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		        exit;
	}
	//update
	$sql = NULL;
	if ($syntax != NULL) {
		if ($example != NULL) {
			$stmt = $mysqli -> prepare('UPDATE record SET syntax = ?, example = ? WHERE id = ?');
			$stmt -> bind_param("ssi", $syntax, $example, $id);
		} else {
			$stmt = $mysqli -> prepare('UPDATE record SET syntax = ? WHERE id = ?');
			$stmt -> bind_param("si", $syntax, $id);
		}
	} else {
		//$example must have content
		$stmt = $mysqli -> prepare('UPDATE record SET example = ? WHERE id = ?');
		$stmt -> bind_param("si", $example, $id);
	}
    
	//echo $sql.";<br>";
	//s -> type string
	$stmt -> execute();

	printf("%d Row updated.\n", $stmt -> affected_rows);

	//Destroy the result set and free the memory used for it
	$stmt -> close();

	//Close the connection
	$mysqli -> close();
}

//update data other than record:lan and content
function updateDataFromDB($data, $table, $servername = "localhost", $username = "xlei", $password = "P123fTlS9n*", $dbname = "xlei_project") {
	//Connect to a MySQL server
	$mysqli = new mysqli($servername, $username, $password, $dbname);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		        exit;
	}
	//update
	if($table == 'language' || $table == 'content'){
		$sql = ('UPDATE ? SET name = ? WHERE name = ?');
		$stmt = $mysqli -> prepare($sql);
		$stmt -> bind_param("sss", $table, $data, $data);
		echo $sql.";<br>";
	}		
	$stmt -> execute();

	printf("%d Row updated.\n", $stmt -> affected_rows);

	//Destroy the result set and free the memory used for it
	$stmt -> close();

	//Close the connection
	$mysqli -> close();
}


function deleteRecordFromDB($id, $servername = "localhost", $username = "xlei", $password = "P123fTlS9n*", $dbname = "xlei_project") {
	//Connect to a MySQL server
	$mysqli = new mysqli($servername, $username, $password, $dbname);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		        exit;
	}
	//delete
	$stmt = $mysqli -> prepare('DELETE FROM record WHERE id = ?');
	$stmt -> bind_param("i", $id);
	//s -> type string
	$stmt -> execute();

	printf("%d Row deleted.\n", $stmt -> affected_rows);

	//Destroy the result set and free the memory used for it
	$stmt -> close();

	//Close the connection
	$mysqli -> close();
}

function deleteLanFromDB($lan, $servername = "localhost", $username = "xlei", $password = "P123fTlS9n*", $dbname = "xlei_project"){
	//Connect to a MySQL server
	$mysqli = new mysqli($servername, $username, $password, $dbname);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		        exit;
	}
	//delete record then lan
	$stmt = $mysqli -> prepare('DELETE FROM record WHERE Language_name = ?');
	$stmt -> bind_param("s", $lan);
	$stmt -> execute();

	printf("%d Row deleted.\n", $stmt -> affected_rows);
	
	$stmt = $mysqli -> prepare('DELETE FROM language WHERE name = ?');
	$stmt -> bind_param("s", $lan);
	$stmt -> execute();

	printf("%d Row deleted.\n", $stmt -> affected_rows);

	//Destroy the result set and free the memory used for it
	$stmt -> close();

	//Close the connection
	$mysqli -> close();
}

function deleteContentFromDB($content, $servername = "localhost", $username = "xlei", $password = "P123fTlS9n*", $dbname = "xlei_project"){
	//Connect to a MySQL server
	$mysqli = new mysqli($servername, $username, $password, $dbname);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		        exit;
	}
	//delete record 
	$stmt = $mysqli -> prepare('DELETE r FROM record r JOIN sub_content sc ON r.sub_content_name = sc.name WHERE sc.content_name = ?');
	$stmt -> bind_param("s", $content);
	$stmt -> execute();

	printf("%d Row deleted.\n", $stmt -> affected_rows);
	
	//delete sub_content
	$stmt = $mysqli -> prepare('DELETE FROM sub_content WHERE content_name = ?');
	$stmt -> bind_param("s", $content);
	$stmt -> execute();

	printf("%d Row deleted.\n", $stmt -> affected_rows);
	
	//delete sub_content
	$stmt = $mysqli -> prepare('DELETE FROM content WHERE name = ?');
	$stmt -> bind_param("s", $content);
	$stmt -> execute();

	printf("%d Row deleted.\n", $stmt -> affected_rows);

	//Destroy the result set and free the memory used for it
	$stmt -> close();

	//Close the connection
	$mysqli -> close();
}


 /**
  * end of the first tier: database
  */

/**
 * second tier: Business
 */
 function createTextPreview($text, $length=25)
{
    return $text;
}



?>