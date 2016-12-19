<?php
	include("database.php");

	// make sure user is logged in and is admin
	session_start();
	if (!(isset($_SESSION["userid"]) && $_SESSION["userisadmin"])) {
		header("Location: /");
		die();
	}

	// check that required fields are set
	if ((isset($_POST["productName"]) && $_POST["productName"] !== "")
	 && (isset($_POST["priceEach"]) && $_POST["priceEach"] !== "")
	 && (isset($_POST["inStock"]) && $_POST["inStock"] !== "")) {
		// insert product details to database
		$productName = $_POST["productName"];
		$priceEach = $_POST["priceEach"];
		$inStock = $_POST["inStock"];
		$query = "INSERT INTO products (productname, priceeach, instock) VALUES ('$productName', $priceEach, $inStock) RETURNING id";
		$result = pg_query($query);

		if ($result) {
			// inserted, add image if included
			if (isset($_FILES["productImage"])) {
				$insertedId = pg_fetch_array($result)["id"];
				$info = pathinfo($_FILES["productImage"]["name"]);
				$target = "../images/products/" . $insertedId . "." . $info["extension"];
				move_uploaded_file($_FILES["productImage"]["tmp_name"], $target);
			}

			// redirect with message
			header("Location: /manage.php?success_message=Product <strong>" . $productName . "</strong> inserted successfully.");
			die();
		} else {
			// error executing insert
			header("Location: /manage.php?error_message=Failed to add product.");
			die();
		}
	}
