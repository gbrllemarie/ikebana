<?php
	include("database.php");

	// make sure user is logged in and is admin
	session_start();
	if (!(isset($_SESSION["userid"]) && $_SESSION["userisadmin"])) {
		header("Location: /");
		die();
	}

	// check that required fields are set
	if ((isset($_POST["productId"]) && $_POST["productId"] !== "")
	 && (isset($_POST["productName"]) && $_POST["productName"] !== "")
	 && (isset($_POST["priceEach"]) && $_POST["priceEach"] !== "")
	 && (isset($_POST["inStock"]) && $_POST["inStock"] !== "")) {
		// modify product details
		$productId = $_POST["productId"];
		$productName = $_POST["productName"];
		$priceEach = $_POST["priceEach"];
		$inStock = $_POST["inStock"];
		$query = "UPDATE products SET (productname, priceeach, instock) = ('$productName', $priceEach, $inStock) WHERE id=$productId";
		$result = pg_query($query);

		if ($result) {
			// updated, update image if included
			if (isset($_FILES["productImage"]) && is_uploaded_file($_FILES["productImage"]["tmp_name"])) {
				// delete old image
				if (file_exists("../images/products/" . $productId . ".jpg")) {
					unlink("../images/products/" . $productId . ".jpg");
				}
				if (file_exists("../images/products/" . $productId . ".png")) {
					unlink("../images/products/" . $productId . ".png");
				}

				$info = pathinfo($_FILES["productImage"]["name"]);
				$target = "../images/products/" . $productId . "." . $info["extension"];
				move_uploaded_file($_FILES["productImage"]["tmp_name"], $target);
			}

			// redirect with message
			header("Location: /manage.php?success_message=Product <strong>" . $productName . "</strong> update successfully.");
			die();
		} else {
			// error executing insert
			header("Location: /manage.php?error_message=Failed to update product.");
			die();
		}
	}
