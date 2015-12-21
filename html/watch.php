<?php
	define("TYNIME", 1);
	require_once("core.php");
	
	function create_links($seriesName, $seriesPos, $videoTitle) {
		//AKB0048 Episode 1 – The Indelible Dream.mp4
		$videoLink = "../videos/" . $seriesName . "/" . $seriesName . " Episode " . $seriesPos . " – " . $videoTitle . ".mp4";
		$subsLink = "../videos/" . $seriesName . "/" . $seriesName . " Episode " . $seriesPos . " – " . $videoTitle . ".vtt";
		return array(
			"videoLink" => $videoLink,
			"subsLink" => $subsLink
		);
	}
	
	
	function get_prev_and_next_links( $seriesName, $currentPos ) {
		global $dbHost, $dbUser, $dbPass, $dbName;
		if(empty($seriesName) || empty($currentPos)) {
			return false;
		}
		
		$out = array();
		$hasError = false;
		
		$db = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
		if( $db->connect_error ) {
			echo("Failed to connect to Database");
			return false;
		}
		
		// GET PREVIOUS LINK
		if(!($query = $db->prepare("SELECT videoId FROM videos, series WHERE seriesName = ? AND series.seriesId = videos.seriesId AND seriesPos < ? ORDER BY seriesPos DESC LIMIT 1"))) {
			echo("Failed to create query");
			$out["prev"] = false;
			$hasError = true;
		}
		
		if(!$hasError) {
			if(!($query->bind_param( "si", $seriesName, $currentPos ))) {
				echo("Failed to bind query params");
				$out["prev"] = false;
				$hasError = true;
			}
		}
		
		if(!$hasError) {
			if(!($query->execute())) {
				echo("Failed to execute query");
				$out["prev"] = false;
				$hasError = true;
			}
		}
		
		if(!$hasError) {
			$query->store_result();
			
			if($query->num_rows == 0) {
				$out["prev"] = false; // no previous link
			} else {
				$query->bind_result( $videoId );
				$query->fetch();
				
				$out["prev"] = "watch.php?v=" . $videoId;
			}
		}
		
		$hasError = false;
		
		// GET NEXT LINK
		if(!($query = $db->prepare("SELECT videoId FROM videos, series WHERE seriesName = ? AND series.seriesId = videos.seriesId AND seriesPos > ? ORDER BY seriesPos ASC LIMIT 1"))) {
			echo("Failed to create query");
			$out["next"] = false;
			$hasError = true;
		}
		
		if(!$hasError) {
			if(!($query->bind_param( "si", $seriesName, $currentPos ))) {
				echo("Failed to bind query params");
				$out["next"] = false;
				$hasError = true;
			}
		}
		
		if(!$hasError) {
			if(!($query->execute())) {
				echo("Failed to execute query");
				$out["next"] = false;
				$hasError = true;
			}
		}
		
		if(!$hasError) {
			$query->store_result();
			
			if($query->num_rows == 0) {
				$out["next"] = false; // no next link
			} else {
				$query->bind_result( $videoId );
				$query->fetch();
				
				$out["next"] = "watch.php?v=" . $videoId;
			}
		}
		
		return $out;
		
	}
	
	function update_view_count($videoId) {
		global $dbHost, $dbUser, $dbPass, $dbName;
		
		$db = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
		if( $db->connect_error ) {
			echo("Failed to connect to Database");
			return false;
		}
		
		if(!($query = $db->prepare("UPDATE videos SET views = views + 1 WHERE videoId = ?;"))) {
			echo("Failed to create query");
			return false;
		}

		if(!($query->bind_param( "i", $videoId))) {
			echo("Failed to bind query params");
			return false;
		}
		
		if(!($query->execute())) {
			echo("Failed to execute query");
			return false;
		}
		
		if($query->affected_rows < 1) {
			echo "failed to update";
		}
	}
	
	if(isset($_GET['v'])) {
		$info = load_video_info($_GET['v']);
	} else  {
		$info = false;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php 
	if($info != false) {
		echo $info["seriesName"] . " Episode " . $info["seriesPos"] . ": " . $info["name"];
	} else {
		echo "404 - Video Not Found ";
	}
	?> - tynime</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<?php addSiteReqs(); ?>
</head>
<body>
	<?php showSiteNavigation(); 
		if($info != false) {
			// add one to our view counter variable
			update_view_count($_GET["v"]);
			
			$links = create_links($info["seriesName"], $info["seriesPos"], $info["name"]);
			$prev_and_next_links = get_prev_and_next_links($info["seriesName"], $info["seriesPos"]);
			?>
	<div class="video-container">
		<h2><?php echo $info["seriesName"]?> <br /> <?php echo "Episode " . $info["seriesPos"] . ": " . $info["name"] ?></h2>
		<video id="video" controls preload="auto" autoplay width="600px" height="390px">
			<source id="videosrc" src="<?php echo $links["videoLink"] ?>" type="video/mp4" >
			<?php
			if($info["hasSubs"]) {
			?>
			<track id="asssrc" label="English" kind="subtitles" srclang="en" src="<?php echo $links["subsLink"] ?>" default />
			<?php
			}
			?>
		</video>
		<div class="links-container center">
			<?php
			if($prev_and_next_links["prev"] != false) {
				?>
				<div class="prev-link">
					<a href="<?php echo $prev_and_next_links["prev"]; ?>">Previous Video</a>
				</div>
			<?php
			}
			if($prev_and_next_links["next"] != false) {
				?>
				<div class="next-link">
					<a href="<?php echo $prev_and_next_links["next"] ; ?>">Next Video</a>
				</div>
				<?php
			} ?>
			
		</div>
		<br />
		<div class="view-counter center">
			<span>Views: <?php if(empty($info["views"])) {
				echo "1";
			} else {
				echo 1 + $info["views"];
			} ?>
		</div>
	</div>
	<?php
		} else {
			echo "<p class=\"error\">Invalid video URL</p>";
		}
	
	?>
	
	
</body>
</html>