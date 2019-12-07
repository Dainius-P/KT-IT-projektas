
<?php
    session_start();

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: index.php");
        exit;
    }

    require_once "generic/config.php";

    $username = $password = "";
    $username_err = $password_err = "";
 
    if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Iveskite prisijungimo varda";
    } else{
        $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Iveskite slaptazodi.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id, username, password FROM vartotojai WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            header("location: index.php");
                        } else{
                            $password_err = "Netinkamas slaptazodis";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "Toks vartotojas neegzistuoja.";
                }
            } else{
                echo "Ivyko klaida.";
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
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>IT projektas - login</title>

    <!-- Bootstrap core CSS -->
    <link href="lib/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/login_style.css" rel="stylesheet">
  </head>
  <body class="text-center">
  	<form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <h1 class="h3 mb-3 font-weight-normal">Prisijunkite</h1>
      <label for="inputUsername" class="sr-only">Prisijungimo vardas</label>
      <input type="text" id="inputUsername" name="username" class="form-control" placeholder="Prisijungimo vardas" required autofocus>
      <span class="help-block"><?php echo $username_err; ?></span>
      <label for="inputPassword" class="sr-only">Slaptazodis</label>
      <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Slaptazodis" required>
      <span class="help-block"><?php echo $password_err; ?></span>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Prisijungti</button>
      <p class="mt-5 mb-3 text-muted">&copy; KTU 2019-2020</p>
  </form>
</body>