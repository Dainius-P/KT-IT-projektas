<?php
	session_start();
	if(empty($_SESSION["loggedin"])){
        header("location: /projektas/login.php");
        exit;
    }
    require_once "config.php";

    $id = $_SESSION['id'];
    $sql = "SELECT * FROM vartotojai WHERE id=$id";
    $result = $link->query($sql);
    $row = $result->fetch_assoc();

    $pareigos = "";

    if($row['admin'] == 1){
        $pareigos = 'Administratorius';
    } elseif ($row['accountant'] == 1){
    	$pareigos = 'Buhalteris';
    } else {
    	$pareigos = 'Klientas';
    }
?>
<nav class="navtop">
	<div>
		<h1>IT Projektas</h1>
		<a href="/projektas/index.php"><i class="fas fa-chart-line"></i>Prietaisu Skydas</a>
		<a href="/projektas/profile.php" style="text-transform: capitalize;"><i class="fas fa-user-circle"></i><?=$_SESSION['username']?> (<?php echo $pareigos; ?>)</a>
        <?php if($pareigos == 'Administratorius'){ echo '<a href="/projektas/accountant/bill_list.php"><i class="fas fa-chart-line"></i>Saskaitu sarasas</a>';} ?>
		<a href="/projektas/logout.php"><i class="fas fa-sign-out-alt"></i>Atsijungti</a>
	</div>
</nav>