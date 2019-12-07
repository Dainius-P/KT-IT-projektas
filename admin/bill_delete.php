<?php
	require_once "../generic/config.php";
	if (isset($_GET['bill_id'])) {
		$bill = $_GET['bill_id'];
	} else {
		header("location: /projektas/index.php");
	}

	$sql = "DELETE FROM saskaitos WHERE id=$bill";
	$link->query($sql);

	$sql = "DELETE FROM saskaitos_prekes WHERE saskaitos_id=$bill";
	$results = $link->query($sql);
	header("location: /projektas/admin/bill_list.php");
?>