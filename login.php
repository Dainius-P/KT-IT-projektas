
<?php
    session_start();

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: dashboard.php");
        exit;
    }

    require_once "config.php";

    $username = $password = "";
    $username_err = $password_err = "";
 
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if ($stmt = $link->prepare('SELECT id, password FROM vartotojai WHERE username = ?')) {
            $stmt->bind_param('s', $_POST['username']);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $password);
                $stmt->fetch();
                if ($_POST['password'] === $password) {
                    session_regenerate_id();
                    $_SESSION['loggedin'] = True;
                    $_SESSION['username'] = $_POST['username'];
                    $_SESSION['id'] = $id;
                    header('Location: dashboard.php');
                } else {
                    $password_err = 'Netinkamas slaptazodis';
                }
            } else {
                $username_err = 'Toks vartotojas neegzistuoja';
            }
        }
        $stmt->close();
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