<?php
	include("database.php");
	session_start();

	// make sure user is logged in
	if (!isset($_SESSION["userid"])) {
		header("Location: /shop.php?error_message=Please log in to access your cart.");
		die();
	}

	// check that required fields are set
	if (isset($_POST["id"]) && $_POST["id"] !== "") {
		// return quantity to store stock
		$id = $_POST["id"];
		$quantity = $_SESSION["usercart"][$id]["quantity"];

		// get current stock amount
		$query = "SELECT instock FROM products WHERE id=$id";
		$result = pg_query($query);
		$inStock = pg_fetch_array($result)["instock"];

		// add stock to db
		$stockLeft = $inStock + $quantity;
		$query = "UPDATE products SET instock = $stockLeft WHERE id = $id";
		$result = pg_query($query);

		// remove from user cart
		unset($_SESSION["usercart"][$id]);

		header("Location: /shop.php?success_message=Item successfully removed from basket.");
		die();

	} else {
		header("Location: /shop.php");
		die();
	}

