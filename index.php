<?php error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php session_start(); ob_start();?>

<!DOCTYPE html>
<html>
	<head>
		<title>My Collection - Syntax Across Languages</title>
		<style>
			table {
				border-collapse: collapse;
				width: 100%;
			}

			th, td {
				padding: 8px;
				text-align: left;
				border-bottom: 1px solid #ddd;
			}
			.tooltip {
				position: relative;
				display: inline-block;
				border-bottom: 1px dotted black;
			}

			.tooltip .tooltiptext {
				visibility: hidden;
				width: 100%;
				background-color: black;
				color: #fff;
				text-align: center;
				border-radius: 6px;
				padding: 5px 0;
				/* Position the tooltip */
				position: absolute;
				z-index: 1;
			}

			.tooltip:hover .tooltiptext {
				visibility: visible;
			}

			.table, .td, .th {
				border: 2px solid #16A085;
				text-align: left;
			}

			.table {
				border-collapse: collapse;
				width: 100%;
			}

			.th, .td {
				padding: 15px;
			}
			.button {
				display: inline-block;
				padding: 10px 20px;
				font-size: 14px;
				cursor: pointer;
				text-align: center;
				text-decoration: none;
				outline: none;
				color: #fff;
				background-color: #28B463;
				border: none;
				border-radius: 15px;
				box-shadow: 0 3px #CFA7B2;
			}

			.button:hover {
				background-color: #F4D03F
			}

			.button:active {
				background-color: #A9DFBF;
				box-shadow: 0 5px #666;
				transform: translateY(4px);
			}

			.title {
				text-align: center;
				padding: 8px;
				color: #1E8449;
				font-size: 60px;
				background-color: #D5F5E3;
			}

			a:link, a:visited {
				background-color: #28B463;
				color: white;
				padding: 14px 25px;
				text-align: center;
				text-decoration: none;
				display: inline-block;
			}

			a:hover, a:active {
				background-color: #A9DFBF;
			}
		</style>
	</head>
	<body>

		<?php
		include 'sql_operations.php';
		/**
		 * display the languages
		 */
		function display_lan($all_lan) {
			$end_ind = count($all_lan) - 1;
			for ($i = 0; $i < count($all_lan); $i++) {
				//control each row has only 4 elements
				if ($i % 4 == 0) {
					echo "<tr>";
					echo "<td>$all_lan[$i]<input type='checkbox' name='lan[]' id='lan' value='$all_lan[$i]'></td>";
				} else {
					echo "<td>$all_lan[$i]<input type='checkbox' name='lan[]' id='lan' value='$all_lan[$i]'></td>";
					if (($i + 1) % 4 == 0 && $i != $end_ind) {
						echo "</tr>";
					}
				}
				//for adding a "All" option in the end of the displaying
				if ($i == $end_ind) {
					echo "<td>All<input type='checkbox' name='lan[]' id='lan' value='all'></td>";
					echo "</tr>";
				}
			}
		}

		/* for add tooltip to each content */
		/*helper function*/
		function display_content($content) {
			echo "<td><div class ='tooltip'> $content<input type='checkbox' name='content[]' id='content' value='$content'>";
			//subcontent
			echo "<span class='tooltiptext'>";
			$sub = searchSub_contentFromDB($content);
			foreach ($sub as $key => $value) {
				echo $value . '<br>';
			}
			echo "</div></span>";
			echo "</td>";
		}

		function display_all_contents($all_content) {
			$counter = 0;
			//for exclude the 'order' vallue
			$end_ind = count($all_content) - 2;
			//becaue last content index is 8, 9 is its order so minus 2
			for ($j = 0; $j < count($all_content); $j++) {
				if ((++$counter) % 2) {
					if ($j % 8 == 0) {
						echo "<tr>";
						display_content($all_content[$j]);
					} else {
						display_content($all_content[$j]);
						if (($j + 1) % 8 == 0 && $j != $end_ind) {
							echo "</tr>";
						}
					}
					//for adding a "All" option in the end of the displaying
					if ($j == $end_ind) {
						echo "<td>All<input type='checkbox' name='content[]' id='content' value='all'></td>";
						echo "</tr>";
					}
				}
			}
		}

		/**
		 * display a table header
		 */
		function table_header($header) {
			echo "<tr>";
			echo "<th colspan='5' style='text-align: left'>$header</th>";
			echo "</tr>";
			echo "<tr class = 'tr'>";
			echo "<th class='th'>Sub Content</th>";
			echo "<th class='th'>Language</th>";
			echo "<th class='th'>Syntax</th>";
			echo "<th class='th'>Example</th>";
			echo "<th class='th'>Edit</th>";
			echo "</tr>";
		}

		/**
		 * display all records in a row with 5 elements
		 */
		/*helper function*/
		function display_record($record) {
			for ($i = 0; $i < count($record); $i++) {
				if ($i % 5 == 0) {
					echo "<tr>";
				} else {
					echo "<td class = 'td'>$record[$i]</td>";
					if (($i + 1) % 5 == 0) {
						//reach the end of one record.
						$value = $record[$i - 4];
						//$value is the record id
						echo "<td class = 'td'><button name = 'update' value = $value>Update</button> <button name = 'delete' value = $value>Delete</button></td>";
						echo "</tr>";
					}
				}
			}
		}

		function display_record_with_edit($record, $id) {
			$value = NULL;
			for ($i = 0; $i < count($record); $i++) {
				//$value is the record id
				if ($i % 5 == 0) {
					$value = $record[$i];
					echo "<tr>";
				} else {
					$name = $i % 5;
					if ($value == $id && ($name == 3 || $name == 4)) {
						//updating mode add text field
						$text = $record[$i];
						echo "<td class = 'td'><input type = 'text' name = '$name' value = '$text'></td>";
						echo $name;
						//debugging
					} else {
						//displaying mode
						echo "<td class = 'td'>$record[$i]</td>";
					}
					//debug here please!!!!!!!
					if (($i + 1) % 5 == 0) {
						//reach the end of one record.

						if ($value == $id) {
							//updating mode
							echo "<td class = 'td'><button name = 'submit' value = $value>Submit</button> <button name = 'cancel' value = $value>Cancel</button></td>";
						} else {
							//displaying mode
							echo "<td class = 'td'><button name = 'update' value = $value>Update</button> <button name = 'delete' value = $value>Delete</button> ";
						}
						echo "</tr>";
					}
				}
			}
		}

		/**
		 * return if a content with some language is found
		 */
		function display_tables_of_contents($all_content, $lan = NULL, $id = NULL) {
			$found = FALSE;
			$i = 0;
			$_SESSION['selected_lan'] = $lan;
			$_SESSION['selected_content'] = $all_content;
			foreach ($all_content as $key => $value) {
				if ((++$i) % 2) {
					echo "<table class='table'>";
					$record_of_one_content = searchRecordFromDB($lan, $value);
					if ($record_of_one_content != NULL) {//only desplay if there is sub_content under the a content
						$found = TRUE;
						table_header($value);
						if ($id == NULL)
							display_record($record_of_one_content);
						else {
							display_record_with_edit($record_of_one_content, $id);
						}
					}
					echo "</table>";
				}
			}
			return $found;
		}

		//remove the order variable of content from db
		function remove_order_value($content_with_order) {
			$selected_content = [];
			foreach ($content_with_order as $key => $value) {
				if (!is_numeric($value)) {
					array_push($selected_content, $value);
				}
			}
			return $selected_content;
		}

		function updateResult($lan_arr, $content_arr) {
			$selected_lan = NULL;
			$selected_content = NULL;
			if (isset($_POST[$lan_arr])) {
				$selected_lan = $_POST[$lan_arr];
			}
			if (isset($_POST[$content_arr])) {
				$selected_content = $_POST[$content_arr];
			}

			emptySelectionWarning($selected_lan, $selected_content);
			// print_r($selected_content);
			//debugging
			// print_r($selected_lan);
			//debugging

			if ($selected_content != NULL && $selected_lan != NULL) {
				if ($selected_content[count($selected_content) - 1] == 'all') {
					$content_with_order = searchALLFromDB('content');
					$selected_content = remove_order_value($content_with_order);
					//remove the order thing

					foreach ($content_with_order as $key => $value) {
						if (!is_numeric($value)) {
							array_push($selected_content, $value);
						}
					}
				}
				if ($selected_lan[count($selected_lan) - 1] == 'all') {
					$selected_lan = searchALLFromDB('language');
				}
				if (!display_tables_of_contents($selected_content, $selected_lan)) {
					echo "<h3>Sorry, no found.</h3>";
				}
			}
		}

		/*helper function*/
		function emptySelectionWarning($selected_lan, $selected_content) {
			$empty = TRUE;
			if ($selected_content == NULL) {
				echo '<script language="javascript">';
				if ($selected_lan == NULL) {
					echo 'alert("Hey, you did not select any content AND language yet!")';
				} else {
					echo 'alert("Hey, you did not select any content yet!")';
				}
				echo '</script>';
			} else if ($selected_lan == NULL) {
				echo '<script language="javascript">';
				echo 'alert("Hey, you did not select any language yet!")';
				echo '</script>';
			} else {
				$empty = FALSE;
			}
			return $empty;
		}

		function content_header($name) {
			echo "<tr>";
			echo "<th colspan='4' style='text-align: center' >$name<th>";
			echo "</tr>";
		}

		function build_Go_Search_Button() {
			echo "<tr>";
			echo "<th colspan='4' style='text-align: center'>";
			echo "<button class = 'button' name = 'submit' value = 'search'>
							Go Search
						</button></th>";
			echo "</tr>";
		}
		?>
		<div class = 'title'>
			My Collection - Syntax Across Languages
		</div>
		<div id= "top bar">
			<table>
				<form id = "search" action = "<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
					<tr>
						<th colspan='4' style='text-align: center'><a href="add.php">Manage(Update, Add, Delete)languages, contents and sub-contents</a></th>
						<?php

						content_header('Language');
						$all_lan = searchALLFromDB('language');
						display_lan($all_lan);
						?>
						<th>
						<br>
						</th>
						<?php
						content_header('Content');
						$all_content = searchALLFromDB('content');
						display_all_contents($all_content);

						build_Go_Search_Button();
						?>
			</table>
			</form>
		</div>
		<div id = "result">
			<form id = "edit" action = "<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
				<?php
				if (!isset($_POST['submit'])) {
					if (isset($_POST['update'])) {
						$_SESSION['id'] = $_POST['update'];
						echo '<br>';
						echo "<br>please refresh the page!!!!!to fix the bug of php<br>";
						display_tables_of_contents($_SESSION['selected_lan'], $_SESSION['selected_content'], $id = $_POST['update']);
						//testing
						header('Location: http://smccs85.com/~xlei/project/index.php');
						exit();
					} else if (isset($_POST['delete'])) {
						$_SESSION['id'] = $_POST['delete'];
						header("Location:http://smccs85.com/~xlei/project/confirm.php");
						exit();

					} else if (isset($_POST['cancel'])) {
						display_tables_of_contents($_SESSION['selected_lan'], $_SESSION['selected_content']);
					} else if (isset($_SESSION['confirm'])) {
						if ($_SESSION['confirm'] == 'yes') {
							deleteRecordFromDB($_SESSION['id']);
							// //deleting
							// echo 'deleted session id ';
							// print_r($_SESSION['id']);
							echo '<br>';
							echo '<script language="javascript">';
							echo 'alert("Your record is deleted successfully")';
							echo '</script>';

							if (!isset($_SESSION['selected_lan'])) {
								display_tables_of_contents($_SESSION['selected_content']);
							} else {
								display_tables_of_contents($_SESSION['selected_content'], $_SESSION['selected_lan']);
							}
						} else {
							display_tables_of_contents($_SESSION['selected_content'], $_SESSION['selected_lan']);
						}
						//empty the session confirm
						$_SESSION['confirm'] = NULL;
					} else {
						display_tables_of_contents($all_content);
					}

				} else {
					if ($_POST['submit'] == 'search') {
						updateResult('lan', 'content');
					} else if (is_numeric($_POST['submit'])) {
						updateRecordFromDB($_SESSION['id'], $_POST['3'], $_POST['4']);
						if (!isset($_SESSION['selected_lan'])) {
							display_tables_of_contents($_SESSION['selected_content']);
						} else {
							display_tables_of_contents($_SESSION['selected_content'], $_SESSION['selected_lan']);
						}
					}
				}
				?>
			</form>
		</div>
	</body>
</html>
