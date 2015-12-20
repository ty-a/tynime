<?php
	define("TYNIME", 1);
	require_once("core.php");
	
	// http://php.net/manual/en/function.session-destroy.php
	$_SESSION = array();
	session_destroy();

	$cookie_params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000, $cookie_params["path"], $cookie_params["domain"],
		$cookie_params["secure"], $cookie_params["httponly"]);
		
	// redirect user back to homepage, and if they aren't redirected, give them a message and link
	header("Location: index.php");
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
	
	<div class="center">You have been logged out; Click <a href="index.php">here</a> to return to the homepage</div>

</body>
</html>