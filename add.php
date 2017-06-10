<?php error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php session_start(); ?>

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
					echo "<td>$all_lan[$i]<input type='radio' name='lan[]' id='lan' value='$all_lan[$i]'></td>";
				} else {
					echo "<td>$all_lan[$i]<input type='radio' name='lan[]' id='lan' value='$all_lan[$i]'></td>";
					if (($i + 1) % 4 == 0 && $i != $end_ind) {
						echo "</tr>";
					}
				}
				//for adding a "All" option in the end of the displaying
				if ($i == $end_ind) {
					echo "<td><input type='text' name='lan_input' value='insert a new Language'>  <button class = 'button' name='add_lan' value = 'lan'>ADD</td>";
					echo "</tr>";
				}
			}
		}

		/* for add tooltip to each content */
		/*helper function*/
		function display_content($content) {
			echo "<td><div class ='tooltip'> $content<input type='radio' name='content[]' id='content' value='$content'>";
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
						echo "<td><input type='text' name='content_input' value='insert a new content'>  <button class = 'button' name='add_content' value = 'content'>ADD</td>";
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

		function content_header($name) {
			echo "<tr>";
			echo "<th colspan='4' style='text-align: center' >$name<th>";
			echo "</tr>";
		}

		function warning($echo) {
			echo '<script language="javascript">';
			echo $echo;
			echo '</script>';
		}

		function default_display($need_sub) {
			content_header('Current Languages');
			$all_lan = searchALLFromDB('language');
			display_lan($all_lan);
			createDeleteButton('lan');

			echo "<th><br></th>";

			content_header('Current Contents');
			$all_content = searchALLFromDB('content');
			display_all_contents($all_content);
			createDeleteButton('content');
			if ($need_sub) {
				if(display_sub_content($_POST['content'])){
					//add record adding section
					echo "<th><br></th>";
					displayAddingRecord();
				}
				$_SESSION['content'] = $_POST['content'][0];
				//get sesscion of content
			}
		}

		function createDeleteButton($value) {
			echo "<tr >";
			echo "<th colspan='4' style='text-align: center'>";
			echo "<button class = 'button' name = 'delete_btn' value = '$value'>
							Delete
						</button>";
			if ($value == 'content') {
				echo " <button class = 'button' name = 'add_btn' value = 'sub_content'>
							Add SubContent
						</button>
						<button class = 'button' name = 'add_btn' value = 'record'>
							Add New Record
						</button>";
			}
			echo "</th></tr>";
		}

		function sub_content_section() {
			content_header('Current Sub');
			$all_lan = searchALLFromDB('language');
			display_lan($all_lan);
			createDeleteButton('lan');

			echo "<th><br></th>";
		}
		/**
		 * return false is there is no sub content
		 */
		function display_sub_content($content) {
			echo "<th><br></th>";
			$content = $content[0];
			content_header($content);
			$all_sub_cont = searchSub_contentFromDB($content);
			$end_ind = count($all_sub_cont) - 1;
			if ($end_ind == -1) {
				echo "<tr>";
				echo "<td><input type='text' name='sub_content_input' value='insert a new sub content'>  <button class = 'button' name='add_sub_content' value = 'lan'>ADD</td>";
				echo "</tr>";
				return FALSE;
			}
			for ($i = 0; $i < count($all_sub_cont); $i++) {
				//control each row has only 4 elements
				if ($i % 4 == 0) {
					echo "<tr>";
					echo "<td>$all_sub_cont[$i]<input type='radio' name='sub_content[]' value='$all_sub_cont[$i]'></td>";
				} else {
					echo "<td>$all_sub_cont[$i]<input type='radio' name='sub_content[]' value='$all_sub_cont[$i]'></td>";
					if (($i + 1) % 4 == 0 && $i != $end_ind) {
						echo "</tr>";
					}
				}
				//for adding a "All" option in the end of the displaying
				if ($i == $end_ind) {
					echo "<td><input type='text' name='sub_content_input' value='insert a new sub content'>  <button class = 'button' name='add_sub_content' value = 'lan'>ADD</td>";
					echo "</tr>";
				}
			}
			createDeleteButton('sub_content');
			return TRUE;
		}
		
		function displayAddingRecord(){
			echo "<tr>";
			echo "<input type='text' name='syntax' value='insert syntax'> <input type='text' name='example' value='insert examples'> 
			<button class = 'button' name='add_record' value = 'record'>ADD</td>";
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
						<th colspan='4' style='text-align: center'><a href="index.php">Home Page</a></th>

						<?php
						$need_sub_cont = FALSE;
						if (isset($_POST['add_lan'])) {//adding language
							if ($_POST['lan_input'] != NULL) {
								insertIntoTabel($_POST['lan_input'], 'language');
							} else {
								warning('alert("Opps~ Empty Input!")');
							}
						} else if (isset($_POST['add_content'])) {//daging content
							if ($_POST['content_input'] != NULL) {
								insertIntoTabel($_POST['content_input'], 'content');
							} else {
								warning('alert("Opps~ Empty Input!")');
							}
						} else if (isset($_POST['delete_btn'])) {//deleting
							if ($_POST['delete_btn'] == 'lan') {
								//deleting lan
								if (isset($_POST['lan'])) {
									deleteLanFromDB($_POST['lan'][0]);
								} else {
									warning('alert("Hmm..You didn\'t select any language to delete")');
								}
							} else if ($_POST['delete_btn'] == 'sub_content') {
								/**
								 * not done!!!!!
								 */

							} else {
								//deleting content
								if (isset($_POST['content'])) {
									deleteContentFromDB($_POST['content'][0]);
								} else {
									warning('alert("Hmm..You didn\'t select any content to delete")');
								}
							}

						} else if (isset($_POST['add_btn'])) {
							if (!isset($_POST['content'])) {
								warning('alert("Hmm..You didn\'t select any content")');
							} else {
								$need_sub_cont = TRUE;
							}
						} else if (isset($_POST['add_sub_content'])) {

							if (!isset($_POST['sub_content_input'])) {
								warning('alert("Opps~ Empty Input!")');
							} else {
								$data = [];
								$data[0] = $_POST['sub_content_input'];
								$data[1] = $_SESSION['content'];
								insertIntoTabel($data, 'sub_content');
							}

						} else if (isset($_POST['add_record'])){
							if (!isset($_POST['lan'])){
								warning('alert("Hmm.. Tell me the language")');
							}else if($_POST['syntax']== ''){
								warning('alert("Hmm.. synstax, please~~")');
							}else if($_POST['sub_content']== NULL){
								warning('alert("Hmm.. Select a sub content")');
							}else{
								$data = [];
								$data[2]="Sammi";
								$data[0] = $_POST['syntax'];
								if($_POST['example'] == ''){
									$data[1] = NULL;
								}else{
									$data[1] = $_POST['example'];
								}
								$data[3] = $_POST['lan'][0];
								$data[4] = $_POST['sub_content'][0];
								insertIntoTabel($data, 'record');
							}
						}
						default_display($need_sub_cont);
						?>
			</table>
			</form>
		</div>
	</body>
</html>
