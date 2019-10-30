<?php
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>IT Projektas - Profilis</title>
		<link href="css/dashboard_style.css" rel="stylesheet" type="text/css">
		<link href="lib/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<?php include 'generic/navbar.php';?>
		<div class="content">
			<h2>Profilis</h2>
			<div>
				<p>Vartotojo duomenys:</p>
				<table>
					<tr>
						<td>Vardas:</td>
						<td style="text-transform: capitalize;"><?=$_SESSION['username']?></td>
					</tr>
					<tr>
						<td>ID:</td>
						<td><?=$_SESSION['id']?></td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>