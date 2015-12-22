<?php
	if(!defined("TYNIME")) {
		die("invalid entry point");
	}
	
	$dbUser = "root";
	$dbPass = "";
	$dbName = "tynime";
	$dbHost = "localhost";
	
	// Maximum number of items in the user's viewing history
	$maxHistorySize = 50;