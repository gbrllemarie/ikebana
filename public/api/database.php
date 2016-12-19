<?php
	function pg_connection_string_from_database_url() {
		extract(parse_url($_ENV["DATABASE_URL"]));
		return "user=$user password=$pass host=$host dbname=" . substr($path, 1);
	}

	if (isset($_ENV["DATABASE_URL"])) {
		$db = pg_connect(pg_connection_string_from_database_url());
	} else {
		$db = pg_connect("host=localhost port=5432 dbname=torres user=root password=root")
			or die("Unable to connect to database: " . pg_last_error());
	}
