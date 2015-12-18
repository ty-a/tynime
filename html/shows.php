<?php
	define("TYNIME", 1);
	require_once("core.php");
	
	function load_shows() {
		global $dbHost, $dbUser, $dbPass, $dbName;
		$out = array();
		
		$db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
		
		// does not accept user input, so no need to use bounded statements
		if($result = $db->query("SELECT seriesId, seriesName FROM series")) {
			while($row = $result->fetch_row()) {
				$out[] = array(
					"seriesId" => $row[0],
					"seriesName" => $row[1]
				);
			}
		}
		return $out;
		
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>tynime - Local Streaming Made Easy</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<?php addSiteReqs(); ?>
</head>
<body>
	<?php showSiteNavigation(); ?>
	<ul>
		<?php
		$shows = load_shows();
		foreach($shows as $show) {
			echo("<li><a href=\"episodes.php?seriesId=" . $show["seriesId"] . "\">" . $show["seriesName"] . "</a></li>");
		}
		?>
	</ul>
</body>
</html>