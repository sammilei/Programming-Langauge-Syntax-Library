<?php error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php session_start(); ?>
<html>
	<head>
		<style>
			.button {
				display: inline-block;
				padding: 10px 20px;
				font-size: 14px;
				cursor: pointer;
				text-align: center;
				text-decoration: none;
				outline: none;
				color: #fff;
				border: none;
				border-radius: 15px;
				box-shadow: 0 3px #CFA7B2;
			}

			.button:hover {
				background-color: #F4D03F
			}
			
		</style>
	</head>
	<body>

		<?php
		if (isset($_POST['confirm'])) {
			if ($_POST['confirm'] == 'Yes') {
				$_SESSION['confirm'] = "yes";
				
			} else if ($_POST['confirm'] == 'No') {
				$_SESSION['confirm'] = "no";
			}
			header("Location:http://smccs85.com/~xlei/project/index.php?");
			exit();
		}
		?>

		<form method="post" action = "">

			<table>
				<tr>
					<th colspan="2">
					<p>
						Are you sure to delete this record?
					</p></th>
				</tr>
				<tr>
					<td>
					<input style = 'background-color: #CB4335' class = 'button' type="submit" name="confirm" value="Yes">
					</td>
					<td>
					<input style ='background-color: #28B463' class = 'button' type="submit" name="confirm" value="No">
					</td>
				</tr>

			</table>
		</form>
