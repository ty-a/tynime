<?php
	define("TYNIME", 1);
	require_once("core.php");
	
	function load_history() {
		global $dbHost, $dbUser, $dbPass, $dbName, $maxHistorySize;
		
		$db = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
		if( $db->connect_error ) {
			echo("Failed to connect to Database");
			return false;
		}
		
		if(!($query = $db->prepare("SELECT viewHistory FROM users WHERE username = ?"))) {
			echo("Failed to create query to get history");
			return false;
		}
		
		if(!($query->bind_param( "s", $_SESSION['username'] ))) {
			echo("Failed to bind query params to get history");
			return false;
		}
		
		if(!($query->execute())) {
			echo("Failed to execute query get history");
			return false;
		}
		
		$query->store_result();
		
		$query->bind_result( $viewHistory );
		$query->fetch();
		
		if(empty($viewHistory)) {
			$history = array();
		} else {
			// We now have the user's view history from the database
			$history = explode(",", $viewHistory);
		}
		
		return $history;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>History - tynime - Local Streaming Made Easy</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<?php addSiteReqs(); ?>
</head>
<body>
	<?php showSiteNavigation();
	
	if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
		echo("Please <a href=\"login.php\">login</a> to view your history.");
	} else {
		$history = load_history();
		if(empty($history)) {
			echo("Go watch something!");
		} else if($history == false) {
			echo("Error loading your history! Please try again later.");
		} else {
			echo("<ul>\n");
			foreach($history as $item) {
				$currVid = load_video_info($item);
				echo("<li><a href=\"watch.php?v=$item\">" . $currVid["seriesName"] . " Episode " . $currVid["seriesPos"] . " – " . $currVid["name"] . "</a></li>\n");
			}
			echo("</ul>");
		}
	}
	?>
</body>
</html>