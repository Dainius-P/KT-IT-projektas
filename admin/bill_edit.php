<?php
	session_start();
	require_once "../generic/config.php";

	if (isset($_GET['bill_id'])) {
        $bill_id = $_GET['bill_id'];
        $sql = "SELECT * FROM saskaitos WHERE id=$bill_id";
        $result = $link->query($sql);
      	$client_results = $result->fetch_assoc();
      	$client_id = $client_results['user_id'];
      	$total_price = $client_results['total_price'];
      	$now = $client_results['date'];
      	$series_number = $client_results['series_number'];
      	$bank_number = $client_results['bank_number'];
      	$bank_title = $client_results['bank_title'];
      	$bank_purpose = $client_results['bank_purpose'];

      	$user_sql = "SELECT username, id FROM vartotojai WHERE id=$client_id";
      	$result = $link->query($user_sql);
      	$user_result = $result->fetch_assoc();
      	$username = $user_result['username'];
    } else {
        header('location: bill_list.php');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	$products = $_POST['productId'];
    	$prices = $_POST['price'];
    	$count = $_POST['count'];
    	$total_price = $_POST['total_price'];
    	$series_number = $_POST['series_number'];
    	$bank_title = $_POST['bank_title'];
    	$bank_number = $_POST['bank_number'];
    	$bank_purpose = $_POST['bank_purpose'];
    	$issuer_id = $_SESSION['id'];


    	$saskaitos_update = "UPDATE saskaitos SET bank_title='$bank_title', bank_number='$bank_number', bank_purpose='$bank_purpose' WHERE id=$bill_id";

		if(mysqli_query($link, $saskaitos_update)){
		    echo "Saskaita redaguota";
		} else{
		    echo "Kazkas negerai. " . mysqli_error($link);
		}

		$delete_sql = "DELETE * FROM saskaitos_prekes WHERE saskaitos_id=$bill_id";

		if(mysqli_query($link, $delete_sql)){
		    echo "Prekes istrintos";
		} else{
		    echo "Kazkas negerai. " . mysqli_error($link);
		}

    	for ($i=0; $i < sizeof($products); $i++) {
    		$sql = "INSERT INTO saskaitos_prekes (saskaitos_id, prekes_id, prekes_kiekis) VALUES ('$saskaitos_id', '$products[$i]', '$count[$i]')";
    		if(mysqli_query($link, $sql)){
			    pass;
			} else{
			    echo "Kazkas negerai. " . mysqli_error($link);
			}
    	}

    	//header("location: ../index.php");
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
		<?php include '../generic/navbar.php';?>
		<form method='POST'>
		<div class="content">
			<h2>Redaguoti saskaita Nr.<?php echo $bill_id; ?></b></h2>
				<div>
					<h2>Klientas</h2>
					<br>
					<table>
						<tr>
							<td>Vardas:</td>
							<td style="text-transform: capitalize;"><?php echo $username; ?></td>
						</tr>
						<tr>
							<td>ID:</td>
							<td><?php echo $client_id; ?></td>
						</tr>
					</table>
				</div>
				<div id="products">
					<h2>Prekes
						<div class="float-right">
							<a class="btn btn-info btn-sm" target="_none" href="product_add.php" role="button">Sukurti preke</a>
						</div>
					</h2>
					<br>
					<select class="form-control" id="products" onchange="addProduct(this)">
						<option value="-1" default>Pasirinkite preke</option>
						<?php
					      	$sql = "SELECT * FROM prekes";
					      	$result = $link->query($sql);
					    	while($row = $result->fetch_assoc()) {
					    		echo '<option value="'.$row['id'].'">'.$row['title'].' | '.$row['price'].' &euro;</option>';
					      	}
						?>
					</select>
					<br>
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
							$result = $link->query($sql);
					    	while($row = $result->fetch_assoc()) {
					    		$product_id = $row['prekes_id'];
					    		echo '<script type="text/javascript"> addProduct('.$product_id.'); </script>';
					    		/*
					    		$prekes_quantity = $row['prekes_kiekis'];
					    		$product_sql = "SELECT * FROM prekes WHERE id=$product_id";
					    		$product_result = $link->query($product_sql);
					    		$product = $product_result->fetch_assoc();
					    		echo '
					    			<tr>
					    			<td>'.$product['id'].'</td>
					    			<td>'.$product['title'].'</td>
					    			<td>'.$product['price'].'</td>
					    			<td>'.$product['description'].'</td>
					    			<td><input type="number" min="1" max="20" value="'.$prekes_quantity.'" name="count[]" class="form-control" onchange="countChange(this, '.$product['price'].', '.$product['id'].')" placeholder="Kiekis"></td>
					    			<td><div class="float-right"><button type="button" onclick="deleteProduct('.$product['price'].')" class="btn btn-danger mb-2 btn-sm">Istrinti produkta</button></div></td>
					    			</tr>
					    		';
					    		*/
					      	}
						?>	
					  	</tbody>
					</table>
					<div class="float-right">
						<input class="form-control" type="number" step="0.1" name="total_price" id="total_price" placeholder="0.00 &euro;" readonly>
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
							<td><input class="form-control" type="text" readonly value="<?php echo $now; ?>"></td>
						</tr>
						<tr>
							<td>Serijos numeris:</td>
							<td><input required class="form-control" name="series_number" type="text" maxlength="12" placeholder="Serijos numeris" value="<?php echo $series_number; ?>" disabled></td>
						</tr>
					</table>
				</div>
				<div id="banko-saskaita">
					<h2>
						Mokejimo informacija
					</h2>
					<table>
						<tr>
							<td>Banko saskaitos numeris:</td>
							<td><input class="form-control" type="text" name="bank_number" value="<?php echo $bank_number; ?>" placeholder="LTXX XXXX XXXX XXXX XXXX" required ></td>
						</tr>
						<tr>
							<td>Moketojo pavadinimas:</td>
							<td><input required class="form-control" name="bank_title" value="<?php echo $bank_title; ?>" type="text" placeholder="Moketojo pavadinimas"></td>
						</tr>
						<tr>
							<td>Mokejimo paskirtis:</td>
							<td><input required class="form-control" name="bank_purpose" value="<?php echo $bank_purpose; ?>" type="text"placeholder="Mokejimo paskirtis"></td>
						</tr>
					</table>
					<div class="float-right"><button type="submit" class="btn btn-danger mb-2 btn-sm">Redaguoti saskaita</button></div>
					<br>
				</div>
			</div>
		</form>
		<script type="text/javascript">
			var prices={};
			function addProduct(product) {
				if(typeof product == 'number'){
					var product_id = product;
				} else {
					var product_id = product.value;
				}
				
				if(product_id == -1){
					return;
				}

				var table = document.getElementById("products-table-body");
				var total_price = document.getElementById("total_price");

				console.log(total_price);

				var row = table.insertRow(-1);
			    var cell1 = row.insertCell(0);
				var cell2 = row.insertCell(1);
				var cell3 = row.insertCell(2);
				var cell4 = row.insertCell(3);
				var cell5 = row.insertCell(4);
				var cell6 = row.insertCell(5);
				var cell7 = row.insertCell(6);

				cell1.innerHTML = table.rows.length;

				jQuery.ajax({
				    type: "GET",
				    url: '../generic/get_product.php',
				    dataType: 'json',
				    data: {product_id: product_id},

				    success: function (obj, textstatus) {
			            cell2.innerHTML = obj.product.title;
			            cell3.innerHTML = obj.product.price;
			            cell4.innerHTML = obj.product.description;

			            total_price.value = (+total_price.value + +obj.product.price).toFixed(2);

						cell5.innerHTML = '<input type="number" min="1" max="20" value="1" name="count[]" class="form-control" onchange="countChange(this, '+obj.product.price+', '+product_id+')" placeholder="Kiekis">';
			            cell7.innerHTML = '<input type="hidden" name="productId[]" value="'+product_id+'"><input type="hidden" name="price[]" value="'+obj.product.price+'">';
			            prices[product_id] = {"quantity": 1, "price": +obj.product.price}
			        }
				});

				cell6.innerHTML = '<div class="float-right"><button type="button" onclick="deleteProduct('+(product_id)+')" class="btn btn-danger mb-2 btn-sm">Istrinti produkta</button></div>';

				var option = document.querySelector('#products option[value="'+product_id+'"]');
				option.setAttribute("disabled", true);
			}
			
			function clientChange() {
				var client_id = document.getElementById("client").value;

				if(client_id != -1){
					document.getElementById("products").style.visibility = "visible";
				}

				location.href = location.origin + location.pathname + '?client_id=' + client_id;
			}

			function countChange(quantity, product_price, product_id){
				prices[product_id] = {"quantity": +quantity.value, "price": +product_price}
				var total_price = document.getElementById("total_price");
				var price = 0

				for (var key in prices) {
					price = price + (prices[key]['quantity']*prices[key]['price'])
				}

				total_price.value = price
			}

			function deleteProduct(product_id){
				var table = document.getElementById("products-table-body");
				var rowCount = table.rows.length;
				for(var i=0; i<rowCount; i++) {
					var row = table.rows[i];
					var prodId = row.cells[6].childNodes[0];
					if(prodId.value == product_id){
						table.deleteRow(i)
						break
					}
				}

				var total_price = document.getElementById("total_price");

				jQuery.ajax({
				    type: "GET",
				    url: '../generic/get_product.php',
				    dataType: 'json',
				    data: {product_id: product_id},

				    success: function (obj, textstatus) {
				    	total_price.value = (+total_price.value - +obj.product.price*prices[product_id]['quantity']).toFixed(2);
				    	delete prices[product_id]
			        }
				});

				var option = document.querySelector('#products option[value="'+product_id+'"]');
				option.disabled = false;
			}
		</script>
	</body>
</html>