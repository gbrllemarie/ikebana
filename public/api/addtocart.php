<?php
	include("database.php");
	session_start();

	// make sure user is logged in
	if (!isset($_SESSION["userid"])) {
		header("Location: /shop.php?error_message=Please log in to access your cart.");
		die();
	}

	// check that required fields are set
	if ((isset($_POST["id"]) && $_POST["id"] !== "")
	 && (isset($_POST["quantity"]) && $_POST["quantity"] !== "")) {
		// check if stock is enough for request
		$id = $_POST["id"];
		$name = $_POST["name"];
		$priceEach = (int)$_POST["priceEach"];
		$quantity = (int)$_POST["quantity"];
		$query = "SELECT instock FROM products WHERE id=$id";
		$result = pg_query($query);
		$inStock = pg_fetch_array($result)["instock"];
		if ($inStock >= $quantity) {
			// stock available, commit order
			$stockLeft = $inStock - $quantity;
			$query = "UPDATE products SET instock = $stockLeft WHERE id = $id";
			$result = pg_query($query);

			// add to user cart
			if (isset($_SESSION["usercart"][$id])) {
				$_SESSION["usercart"][$id]["quantity"] = $_SESSION["usercart"][$id]["quantity"] + $quantity;
			} else {
				$_SESSION["usercart"][$id] = array();
				$_SESSION["usercart"][$id]["name"] = $name;
				$_SESSION["usercart"][$id]["priceEach"] = $priceEach;
				$_SESSION["usercart"][$id]["quantity"] = $quantity;
			}

			// redirect to homepage
			header("Location: /shop.php?success_message=Item added to basket.");
			die();
		} else {
			// not enough stock
			header("Location: /shop.php?error_message=Insufficient stocks available.");
			die();
		}
	} else {
		header("Location: /shop.php");
		die();
	}

