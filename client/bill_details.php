<?php
	session_start();
	if (isset($_GET['bill_id'])) {
      $bill_id = $_GET['bill_id'];
  	} else {
      	header("location: ../index.php");
  	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>IT Projektas - Profilis</title>
		<link href="../css/dashboard_style.css" rel="stylesheet" type="text/css">
		<link href="../lib/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	</head>
	<body class="loggedin">
		<?php include '../generic/navbar.php'; ?>
		<div class="content">
			<div>
				<h2>Klientas</h2>
				<br>
				<table>
					<?php
						$sql = "SELECT * FROM saskaitos WHERE id=$bill_id";
						$bill_result = $link->query($sql);
						$bill_row = $bill_result->fetch_assoc();
						$user_id = $bill_row['user_id'];
						$total_price = $bill_row['total_price'];
						$date = $bill_row['date'];
						$series_number = $bill_row['series_number'];
						$bank_number = $bill_row['bank_number'];
						$bank_title = $bill_row['bank_title'];
						$bank_purpose = $bill_row['bank_purpose'];

						$username_sql = "SELECT username FROM vartotojai where id=$user_id";
						$user_result = $link->query($username_sql);
						$user_row = $user_result->fetch_assoc();

						echo '<tr>';
						echo '<td>Vardas: </td><td style="text-transform: capitalize;">'.$user_row['username'].'</td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>ID:</td><td>'.$user_id.'</td>';
						echo '</tr>';
					?>
				</table>
			</div>
			<div id="products">
				<h2>Prekes
				</h2>
				<table class="table" id="products-table">
					<thead>
					    <tr>
					      <th scope="col">#</th>
					      <th scope="col">Pavadinimas</th>
					      <th scope="col">Kaina</th>
					      <th scope="col">Aprasas</th>
					      <th scope="col">Kiekis</th>
					      <th scope="col"></th>
					    </tr>
				  	</thead>
				  	<tbody id="products-table-body">
				  		<?php
							$sql = "SELECT * FROM saskaitos_prekes WHERE saskaitos_id=$bill_id";
							$bill_result = $link->query($sql);
							$ind = 1;

							while($row = $bill_result->fetch_assoc()) {
								$product_id = $row['prekes_id'];
								$product_sql = "SELECT * FROM prekes WHERE id=$product_id";
								$product_results = $link->query($product_sql);
								$product_row = $product_results->fetch_assoc();

								echo '<tr>';
								echo '<td>'.$ind.'</td>';
								echo '<td>'.$product_row['title'].'</td>';
								echo '<td>'.$product_row['price'].'</td>';
								echo '<td>'.$product_row['description'].'</td>';
								echo '<td><input type="number" value="'.$row['prekes_kiekis'].'" readonly class="form-control"></td>';
								echo '</tr>';
								$ind = $ind + 1;
							}
				  		?>
				  	</tbody>
				</table>
				<div class="float-right">
					<input class="form-control" type="number" step="0.1" value="<?php echo $total_price; ?>" name="total_price" id="total_price" readonly>
				</div>
				<br>
			</div>
			<div id="saskaita">
				<h2>
					Saskaita
				</h2>
				<table>
					<tr>
						<td>Saskaitos data:</td>
						<td><?php echo $date; ?></td>
					</tr>
					<tr>
						<td>Serijos numeris:</td>
						<td><?php echo $series_number; ?></td>
					</tr>
				</table>
			</div>
			<div>
				<h2>
					Mokejimo duomenys
				</h2>
				<table>
					<tr>
						<td>Banko saskaitos numeris: </td>
						<td>LT 137 212348 531653</td>
					</tr>
					<tr>
						<td>Pavadinimas: </td>
						<td>localhost</td>
					</tr>
					<tr>
						<td>Mokejimo paskirtis: </td>
						<td>bill_id</td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>