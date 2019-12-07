<?php
	session_start();

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		require_once "config.php";

		$sql = "INSERT INTO prekes (title, price, description) VALUES (?, ?, ?)";
		if($stmt = mysqli_prepare($link, $sql)){
			mysqli_stmt_bind_param($stmt, "sds", $param_title, $param_price, $param_description);

		    $param_title = $_POST["title"];
		    $param_price = $_POST["price"];
		    $param_description = $_POST["description"];

		            
	        if(mysqli_stmt_execute($stmt)){
	            header("location: dashboard.php");
	        } else{
	            echo "Kazkas negerai.";
	        }
	    }
        mysqli_stmt_close($stmt);
	}
	mysqli_close($link);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>IT Projektas - Sukurti preke</title>
		<link href="css/dashboard_style.css" rel="stylesheet" type="text/css">
		<link href="lib/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<?php include 'generic/navbar.php';?>
		<div class="content">
			<h2>Sukurti preke</h2>
			<br>
			<form method="POST">
			  <div class="form-group row">
			    <label for="productTitle" class="col-sm-2 col-form-label">Prekes pavadinimas</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" name="title" placeholder="Prekes pavadinimas" required>
			    </div>
			  </div>
			  <div class="form-group row">
			    <label for="kaina" class="col-sm-2 col-form-label">Kaina</label>
			    <div class="col-sm-10">
			      <input type="number" class="form-control" min="0" value="0" step=".01" name="price" placeholder="Kaina" required>
			    </div>
			  </div>
			  <div class="form-group row">
			    <label for="aprasas" class="col-sm-2 col-form-label">Aprasas</label>
			    <div class="col-sm-10">
			      <textarea type="text" class="form-control" name="description" placeholder="Aprasas" required></textarea>
			    </div>
			  </div>
			  <div class="float-right"><button type="submit" class="btn btn-primary mb-2">Sukurti preke</button></div>
			</form>
		</div>
	</body>
</html>