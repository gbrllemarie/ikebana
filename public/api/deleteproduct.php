<?php
	include("database.php");

	// make sure user is logged in and is admin
	session_start();
	if (!(isset($_SESSION["userid"]) && $_SESSION["userisadmin"])) {
		header("Location: /");
		die();
	}

	// check that required fields are set
	if (isset($_POST["productId"])) {
		$id = $_POST["productId"];
		$query = "DELETE FROM products WHERE id=$id RETURNING productname";
		$result = pg_query($query);
		if ($result) {
			// delete photo if exists
			if (file_exists("../images/products/" . $id . ".jpg")) {
				unlink("../images/products/" . $id . ".jpg");
			}
			if (file_exists("../images/products/" . $id . ".png")) {
				unlink("../images/products/" . $id . ".png");
			}
			$name = pg_fetch_array($result)["productname"];
			header("Location: /manage.php?success_message=Product <strong>" . $name . "</strong> successfully deleted.");
			die();
		} else {
			header("Location: /manage.php?error_message=Failed to delete product.");
			die();
		}
	} else {
		header("Location: /manage.php");
		die();
	}
