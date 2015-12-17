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
?>
<!DOCTYPE html>
<html>
<head>
	<title>tynime - Local Streaming Made Easy</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<?php addSiteReqs(); ?>
</head>
<body>
	<?php showSiteNavigation(); 
		if(isset($_GET['v'])) {
			// load up our video
			//var_dump(load_video_info($_GET['v']));
			$info = load_video_info($_GET['v']);
			$links = create_links($info["seriesName"], $info["seriesPos"], $info["name"]);
			
			?>
	<div class="video-container">
		<h2><?php echo $info["seriesName"]?> <br /> <?php echo "Episode " . $info["seriesPos"] . ": " . $info["name"] ?></h2>
		<video id="video" controls preload="auto" autoplay width="600px" height="390px">
			<source id="videosrc" src="<?php echo $links["videoLink"] ?>" type="video/mp4" >
			<track id="asssrc" label="English" kind="subtitles" srclang="en" src="<?php echo $links["subsLink"] ?>" default />
		</video>
		<div class="links-container center">
			<div class="prev-link">
				<a href="awef">Previous Video</a>
			</div>
			<div class="next-link">
				<a href="awef">Next Video</a>
			</div>
		</div>
	</div>
	<?php
		} else {
			echo "<p class=\"error\">Invalid video URL</p>";
		}
	
	?>
	
	
</body>
</html>