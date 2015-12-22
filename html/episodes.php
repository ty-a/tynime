<?php
	define("TYNIME", 1);
	require_once("core.php");
	
	function loadSeriesInfo($seriesId) {
		global $dbHost, $dbUser, $dbPass, $dbName;
		if(empty($seriesId)) {
			return false;
		}
		
		$db = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
		if( $db->connect_error ) {
			echo("Failed to connect to Database");
			return false;
		}
		
		if(!($query = $db->prepare("SELECT seriesName FROM series WHERE seriesId = ?"))) {
			echo("Failed to create query");
			return false;
		}
		
		if(!($query->bind_param( "i", $seriesId ))) {
			echo("Failed to bind query params");
			return false;
		}
		
		if(!($query->execute())) {
			echo("Failed to execute query");
			return false;
		}
		
		$query->store_result();
		
		if($query->num_rows == 0) {
			echo("Show not found");
			return false;
		}
		
		$query->bind_result( $seriesName );
		$query->fetch();
		return $seriesName;
	}
	
	if(isset($_GET['seriesId'])) {
		$title = loadSeriesInfo($_GET['seriesId']);
		if(!$title) {
			$title = "tynime - Local Streaming Made Easy";
			$showName = false;
		} else {
			$showName = $title;
			$title .= " - tynime - Local Streaming Made Easy";
		}
		
	} else {
		$title = "tynime - Local Streaming Made Easy";
	}
	
	// Returns array sorted by seriesPosition
	function loadEpisodes($seriesId) {
		global $dbHost, $dbUser, $dbPass, $dbName;
		$out = array();
		
		// validate input
		if(!is_numeric($seriesId)) {
			echo "invalid series id";
			return false;
		}
		
		$db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
		
		if($result = $db->query("SELECT videoId, name, seriesPos FROM videos WHERE seriesId = " . $seriesId . " ORDER BY seriesPos ASC")) {
			while($row = $result->fetch_row()) {
				$out[] = array(
					"videoId" => $row[0],
					"name" => $row[1],
					"position" => $row[2]
				);
			}
		}
		return $out;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title ?></title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<?php addSiteReqs(); ?>
</head>
<body>
	<?php showSiteNavigation(); ?>
	<ul>
		<?php
		$episodes = loadEpisodes($_GET["seriesId"]);
		$numOfEpisodes = count($episodes);
		if(!$episodes || $numOfEpisodes == 0) {
			die("No episodes available");
		}

		foreach($episodes as $episode) {
			echo "<li><a href=\"watch.php?v=" . $episode["videoId"] . "\">" . $showName . " Episode " . $episode["position"] . " – " . $episode["name"] . "</a></li>";
		}
		?>
	</ul>
</body>
</html>