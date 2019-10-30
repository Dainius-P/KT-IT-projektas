<?php
	session_start();
	if(empty($_SESSION["loggedin"])){
        header("location: login.php");
        exit;
    }
?>
<nav class="navtop">
	<div>
		<h1>IT Projektas</h1>
		<a href="dashboard.php"><i class="fas fa-chart-line"></i>Prietaisu Skydas</a>
		<a href="profile.php" style="text-transform: capitalize;"><i class="fas fa-user-circle"></i><?=$_SESSION['username']?></a>
		<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Atsijungti</a>
	</div>
</nav>