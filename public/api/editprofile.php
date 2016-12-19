<?php
	include("database.php");
	session_start();

	if (!isset($_SESSION["userid"])) {
		// -- redirect to homepage
		header("Location: /login_error?You must be logged in to access this page.");
		die();
	}

	if (isset($_POST["password"])) {
		$inputUsername = $_POST["username"];
		$inputPassword = $_POST["password"];
		$inputNewPassword = $_POST["newPassword"];

		// verify entered password
		$userId = $_SESSION["userid"];
		$query = "SELECT password FROM users WHERE id=$userId";
		$result = pg_query($query);
		$storedPassword = pg_fetch_array($result)["password"];
		if (password_verify($inputPassword, $storedPassword)) {
			// user entered a new username
			$newUsername = $_SESSION["username"];
			if ($inputUsername !== "" && $inputUsername !== $_SESSION["username"]) {
				$newUsername = $inputUsername;
			}

			// user entered a new password
			$newPassword = $storedPassword;
			if ($inputNewPassword !== "") {
				$newPassword = password_hash($inputNewPassword, PASSWORD_DEFAULT);
			}

			// update user profile
			$query = "UPDATE users SET (username, password) = ('$newUsername', '$newPassword') WHERE id = $userId";
			$result = pg_query($query);

			if ($result) {
				// update session username
				$_SESSION["username"] = $newUsername;

				header("Location: /profile.php?success_message=Successfully updated profile.");
				die();
			} else {
				header("Location: /profile.php?error_message=Failed to update profile.");
				die();
			}
		} else {
		header("Location: /profile.php?error_message=Wrong password provided.");
		die();
		}
	} else {
		header("Location: /profile.php");
		die();
	}
