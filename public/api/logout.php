<?php
	session_start();

	$_SESSION = array();

	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}

	session_destroy();

	// redirect to homepage
	header("Location: /?logout_success=You have been successfully logged out.");
	die();
