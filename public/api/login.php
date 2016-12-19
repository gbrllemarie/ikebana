<?php
	include("database.php");

	if (isset($_POST["username"]) && isset($_POST["password"])) {
		// -- login user
		$inputUsername = $_POST["username"];
		$inputPassword = $_POST["password"];
		$query = "SELECT * FROM users WHERE username='$inputUsername'";
		$result = pg_query($query);
		$row = pg_fetch_array($result);

		if ($row) {
			// user exists, validate pasword
			if (password_verify($inputPassword, $row["password"])) {
				// user authenticated, begin session
				session_start();
				$_SESSION["userid"] = $row["id"];
				$_SESSION["username"] = $row["username"];
				$_SESSION["userisadmin"] = $row["isadmin"] === "t";
				// redirect to homepage
				header("Location: /");
				die();
			} else {
				// wrong password
				header("Location: /?login_error=Wrong password.");
				die();
			}
		} else {
			// user doesn't exist
			header("Location: /?login_error=User doesn't exist.");
			die();
		}
	} else {
		// -- redirect to homepage
		header("Location: /");
		die();
	}
