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
        <?php
            require_once "../generic/config.php";
        ?>
		<div class="content">
		  <?php
		      $id = $_SESSION['id'];
			  $sql = "SELECT * FROM vartotojai WHERE id=$id";
			    $result = $link->query($sql);
			    $row = $result->fetch_assoc();

			    if($row['admin'] != 1){
			        header("location: /projektas/index.php");
			    }
		      echo '<h2>Sąskaitu sarasas</h2>';

		      echo '<br>
		            <table class="table">
		                <thead class="thead-dark">
		                  <tr>
		                      <th scope="col">#</th>
		                      <th scope="col">Saskaita israse</th>
		                      <th scope="col">Saskaitos gavejas</th>
		                      <th scope="col">Israsymo data</th>
		                      <th scope="col">Suma</th>
		                      <th scope="col">Redaguoti</th>
		                      <th scope="col">Istrinti</th>
		                  </tr>
		                 </thead>
		              <tbody>';
		      if (isset($_GET['page'])) {
		          $page = $_GET['page'];
		      } else {
		          $page = 1;
		      }

		      $no_of_records_per_page = 10;
		      $offset = ($page-1) * $no_of_records_per_page;


		      $total_pages_sql = "SELECT COUNT(*) as count FROM saskaitos";
		      $result = $link->query($total_pages_sql);
		      $row = $result->fetch_assoc();
		      $total_rows = $row['count'];
		      $total_pages = ceil($total_rows / $no_of_records_per_page);
		      $sql = "SELECT * FROM saskaitos ORDER BY date desc LIMIT $offset, $no_of_records_per_page";

		      $result = $link->query($sql);
		      while($row = $result->fetch_assoc()) {

		            $user_sql = "SELECT username FROM vartotojai WHERE id=".$row['user_id'];
		            $user_result = $link->query($user_sql);
		            $user_row = $user_result->fetch_assoc();

		            $issuer_sql = "SELECT username FROM vartotojai WHERE id=".$row['user_created'];
		            $issuer_result = $link->query($issuer_sql);
		            $issuer_row = $issuer_result->fetch_assoc();

		            echo '<tr>
		            <th scope="row">'.$row['id'].'</th>
		            <td>'.$issuer_row['username'].'</td>
		            <td>'.$user_row['username'].'</td>
		            <td>'.$row['date'].'</td>
		            <td>'.$row['total_price'].' &euro;</td>
		            <td><a class="btn btn-primary btn-sm" href="bill_edit.php?bill_id='.$row['id'].'"role="button">Redaguoti</a></td>
		            <td><a class="btn btn-danger btn-sm" href="bill_delete.php?bill_id='.$row['id'].'"role="button">Istrinti</a></td>
		          </tr>';
		      }

		      echo "</tbody></table>";
		      $link->close();
		  ?>
		  <nav>
		    <ul class="pagination justify-content-center">
		      <li class="page-item"><a class="page-link" href="?page=1">First</a></li>
		      <li class="<?php if($page <= 1){ echo 'disabled'; } ?> page-item">
		          <a class="page-link" href="<?php if($page <= 1){ echo '#'; } else { echo "?page=".($page - 1); } ?>">Prev</a>
		      </li>
		      <li class="<?php if($page >= $total_pages){ echo 'disabled'; } ?> page-item">
		          <a class="page-link" href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=".($page + 1); } ?>">Next</a>
		      </li>
		      <li class="page-item"><a class="page-link" href="?page=<?php echo $total_pages; ?>">Last</a></li>
		    </ul>
		  </nav>
		</div>
    </body>
</html>