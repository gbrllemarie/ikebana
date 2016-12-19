<?php
	$db =  pg_connect("host=localhost port=5432 dbname=ikebana user=root password=root")
		or die("Unable to connect to database: " . pg_last_error());
