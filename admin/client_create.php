<?php
	session_start();

	$id = $_SESSION["id"];

	require_once "../generic/config.php";

	$sql = "SELECT * FROM vartotojai WHERE id=$id";
	$result = $link->query($sql);
	$row = $result->fetch_assoc();

    if($row['admin'] == 0){
        header("location: dashboard.php");
        exit;
    }

	$username = $password1 = $password2 = "";
	$username_err = $password1_err = $password2_err = "";
	 
	if($_SERVER["REQUEST_METHOD"] == "POST"){
	 
	    // Validate username
	    if(empty(trim($_POST["username"]))){
	        $username_err = "Iveskite slaptazodi";
	    } else{
	        $sql = "SELECT id FROM vartotojai WHERE username = ?";
	        
	        if($stmt = mysqli_prepare($link, $sql)){
	            mysqli_stmt_bind_param($stmt, "s", $param_username);
	            $param_username = trim($_POST["username"]);
	            if(mysqli_stmt_execute($stmt)){
	                mysqli_stmt_store_result($stmt);
	                
	                if(mysqli_stmt_num_rows($stmt) == 1){
	                    $username_err = "Toks vartotojas jau egzistuoja.";
	                } else{
	                    $username = trim($_POST["username"]);
	                }
	            } else{
	                echo "Kazkas negerai.";
	            }
	        }
	        mysqli_stmt_close($stmt);
	    }

	    if(empty(trim($_POST["password1"]))){
	        $password1_err = "Iveskite slaptazodi";     
	    } elseif(strlen(trim($_POST["password1"])) < 6){
	        $password1_err = "Slaptazodis turi buti bent 6 elementu ilgio";
	    } else{
	        $password1 = trim($_POST["password1"]);
	    }
	    
	    if(empty(trim($_POST["password2"]))){
	        $password2_err = "Pakartokite slaptazodi";     
	    } else{
	        $password2 = trim($_POST["password2"]);
	        if(empty($password1_err) && ($password1 != $password2)){
	            $password2_err = "Slaptazodziai nesutampa";
	        }
	    }
	    
	    if(empty($username_err) && empty($password1_err) && empty($password2_err)){
	        
	        $sql = "INSERT INTO vartotojai (username, password, client) VALUES (?, ?, 1)";
	         
	        if($stmt = mysqli_prepare($link, $sql)){
	            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

	            $param_username = $username;
	            $param_password = password_hash($password1, PASSWORD_DEFAULT);
	            
	            if(mysqli_stmt_execute($stmt)){
	                header("location: login.php");
	            } else{
	                echo "Kazkas negerai.";
	            }
	        }
	         
	        // Close statement
	        mysqli_stmt_close($stmt);
	    }
	    
	    // Close connection
	    mysqli_close($link);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>IT Projektas - Dashboard</title>
		<link href="../css/dashboard_style.css" rel="stylesheet" type="text/css">
		<link href="../lib/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<?php include '../generic/navbar.php';?>
		<div class="content">
			<h2>Kliento sukurimas</h2>
			<br>
			<form method="POST">
			  <div class="form-group row">
			    <label for="vartotojoV" class="col-sm-2 col-form-label">Vartojo vardas</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control <?php if(isset($_SESSION['username_error'])){ echo 'is-invalid'; } ?>" name="username" placeholder="Vartotojo Vardas" required>
			    </div>
			  </div>
			  <div class="form-group row">
			    <label for="password1" class="col-sm-2 col-form-label">Slaptazodis</label>
			    <div class="col-sm-10">
			      <input type="password" class="form-control" name="password1" placeholder="Slaptazodis" required>
			    </div>
			  </div>
			  <div class="form-group row">
			    <label for="password2" class="col-sm-2 col-form-label">Slaptazodis (Pakartoti)</label>
			    <div class="col-sm-10">
			      <input type="password" class="form-control" name="password2" placeholder="Slaptazodis (Pakartoti)" required>
			    </div>
			  </div>
			  <div class="float-right"><button type="submit" class="btn btn-primary mb-2">Sukurti</button></div>
			</form>
		</div>
	</body>
</html>