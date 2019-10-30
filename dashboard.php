<?php
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>IT Projektas - Dashboard</title>
		<link href="css/dashboard_style.css" rel="stylesheet" type="text/css">
		<link href="lib/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<?php include 'generic/navbar.php';?>
		<div class="content">
			<?php
				require_once "config.php";
				$username = $_SESSION['username'];
				$sql = "SELECT admin FROM vartotojai WHERE username = ?";

        		if($stmt = mysqli_prepare($link, $sql)){
            		mysqli_stmt_bind_param($stmt, "s", $username);
            		mysqli_stmt_execute($stmt);
            		mysqli_stmt_bind_result($stmt, $admin);
            		mysqli_stmt_fetch($stmt);
					mysqli_stmt_close($stmt);
				}

				if($admin == 1){
					echo '
						<h2>Klientu sarasas</h2>
						<br>
						<table class="table">
						  <thead class="thead-dark">
						    <tr>
						      <th scope="col">#</th>
						      <th scope="col">First</th>
						      <th scope="col">Last</th>
						      <th scope="col">Handle</th>
						    </tr>
						  </thead>
						  <tbody>
						    <tr>
						      <th scope="row">1</th>
						      <td>Mark</td>
						      <td>Otto</td>
						      <td>@mdo</td>
						    </tr>
						    <tr>
						      <th scope="row">2</th>
						      <td>Jacob</td>
						      <td>Thornton</td>
						      <td>@fat</td>
						    </tr>
						    <tr>
						      <th scope="row">3</th>
						      <td>Larry</td>
						      <td>the Bird</td>
						      <td>@twitter</td>
						    </tr>
						  </tbody>
						</table>';
				}
			?>
		</div>
	</body>
</html>