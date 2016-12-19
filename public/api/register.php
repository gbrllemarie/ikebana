<?php
	include("database.php");

	if (isset($_POST["username"]) && isset($_POST["password"])) {
		$inputUsername = $_POST["username"];
		$inputPassword = $_POST["password"];
		// check if user exists
		$query = "SELECT * FROM users WHERE username='$inputUsername'";
		$result = pg_query($query);
		$row = pg_fetch_array($result);

		if (!$row) {
			// user doesn't exist, create user
			$hashedPassword = password_hash($inputPassword, PASSWORD_DEFAULT);
			$query = "INSERT INTO users (username, password, isadmin) VALUES ('$inputUsername', '$hashedPassword', false)";
			$result = pg_query($query);
			if ($result) {
				// user created, redirect to homepage
				header("Location: /?register_success=User $inputUsername created. You may now login.");
				die();
			} else {
				// failed to create user
				header("Location: /?register_error=Registration failed.");
				die();
			}
		} else {
			// user already exists
			header("Location: /?register_error=User already exist.");
			die();
		}
	} else {
		// -- redirect to homepage
		header("Location: /");
		die();
	}
