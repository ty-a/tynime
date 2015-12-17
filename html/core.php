<?php

error_reporting(E_ALL); 
ini_set('display_errors', '1');

require_once("config.php");

function showSiteNavigation() {
	?><nav class="navigation">
		<ul class="navigation-list">
			<li><a href="index.php" alt="tynime" class="home-link">tynime</a></li>
			<li><a href="shows.php" alt="shows" class="shows-link">Shows</a></li>
			<li><a href="login.php" alt="login" class="login-link">Login</a></li>
		</ul>
	</nav>
	<?php
}

function addSiteReqs() {
	?><script src="js/video.js"></script>
	<link rel="stylesheet" href="css/main.css" type="text/css">
	<?php
}

function load_video_info($videoId) {
	global $dbHost, $dbUser, $dbPass, $dbName;
	if(empty($videoId)) {
		return false;
	}
	
	$db = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
	if( $db->connect_error ) {
		echo("Failed to connect to Database");
		return false;
	}
	
	if(!($query = $db->prepare("SELECT views,name,seriesPos,seriesId FROM videos WHERE videoId = ?"))) {
		echo("Failed to create query");
		return false;
	}
	
	if(!($query->bind_param( "i", $videoId ))) {
		echo("Failed to bind query params");
		return false;
	}
	
	if(!($query->execute())) {
		echo("Failed to execute query");
		return false;
	}
	
	$query->store_result();
	
	if($query->num_rows == 0) {
		echo("Video not found");
		return false;
	}
	
	$query->bind_result( $viewCount, $videoName, $seriesPos, $seriesId );
	$query->fetch();
	
	// GET SERIES NAME 
	if(!($query = $db->prepare("SELECT seriesName FROM series WHERE seriesId = ?"))) {
		echo("Failed to create query");
		return false;
	}
	
	if(!($query->bind_param( "i", $seriesId ))) {
		echo("Failed to bind query params 2");
		return false;
	}
	
	if(!($query->execute())) {
		echo("Failed to execute query 2");
		return false;
	}
	
	$query->store_result();
	
	$query->bind_result( $seriesName );
	$query->fetch();
	
	return array(
		"views" => $viewCount,
		"name" => $videoName,
		"seriesPos" => $seriesPos,
		"seriesName" => $seriesName
	);
}