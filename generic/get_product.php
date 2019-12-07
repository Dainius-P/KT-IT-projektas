<?php
	if( isset($_GET['product_id'])){
		require_once "config.php";

		$product_id = $_GET['product_id'];
		$product = array();
		$sql = "SELECT * FROM prekes WHERE id=".$product_id;
		$result = $link->query($sql);
        $row = $result->fetch_assoc();

		$product['product'] = $row;

		echo json_encode($product);
	}
?>