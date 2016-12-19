<?php
	include("database.php");

	// make sure user is logged in
	session_start();
	if (!isset($_SESSION["userid"])) {
		header("Location: /");
		die();
	}

	$total = 0;
	$orderDetailsQuery = "INSERT INTO orderdetails (orderid, productname, quantityordered, totalamount) VALUES";
	foreach ($_SESSION["usercart"] as $id => $details) {
		// add row to orderdetails query
		$name = $details["name"];
		$quantity = $details["quantity"];
		$itemTotal = $details["priceEach"] * $quantity;
		$total += $itemTotal;
		$orderDetailsQuery .= " (%ORDER_ID_PLACEHOLDER%, '$name', $quantity, $itemTotal),";
	}
	// remove last comma
	$orderDetailsQuery = rtrim($orderDetailsQuery, ",");

	// add order row
	$userId = $_SESSION["userid"];
	$orderQuery = "INSERT INTO orders (userId, totalAmount) VALUES ($userId, $total) RETURNING id";
	$result = pg_query($orderQuery);

	if ($result) {
		// add orderdetails rows
		$orderId = pg_fetch_array($result)["id"];
		$orderDetailsQuery = str_replace("%ORDER_ID_PLACEHOLDER%", $orderId, $orderDetailsQuery);
		$result = pg_query($orderDetailsQuery);

		// clear user cart
		unset($_SESSION["usercart"]);

		header("Location: /profile.php");
		die();
	}

