<?php
	define("TYNIME", 1);
	require_once("core.php");
	
	function attempt_login($user, $pass) {
		global $dbHost, $dbUser, $dbPass, $dbName;
		
		$db = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
		if( $db->connect_error ) {
			echo("Failed to connect to Database");
			return false;
		}
		
		if(!($query = $db->prepare("SELECT password FROM users WHERE username = ?;"))) {
			echo("Failed to create query");
			return false;
		}

		if(!($query->bind_param( "s", $user))) {
			echo("Failed to bind query params");
			return false;
		}
		
		if(!($query->execute())) {
			echo("Failed to execute query");
			return false;
		}
		
		$query->store_result();
		
		if($query->num_rows === 0) {
			echo("Username does not exist!");
			return false;
		}
		
		$query->bind_result( $passwordHash );
		$query->fetch();
		
		return password_verify( $pass, $passwordHash );
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login - tynime - Local Streaming Made Easy</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<?php addSiteReqs(); ?>
</head>
<body>
	<?php showSiteNavigation();
	if(empty($_POST)) {
	?>
	<div id="login-container" class="center">
		<form id="login-form" action="login.php" method="POST">
			<label for="username-text">Username</label>
			<input type="text" id="username-text" name="username" required></input>
			
			<label for="password-text">Password</label>
			<input type="password" id="password-text" name="password" required></input>
			
			<input type="submit" class="center" />
		</form>
	</div>
	<?php } else { // FORM WAS POSTED, YO
		// Only checking for those web browsers that do not support the required attribute or for users attempting to bot the form
		if( !isset($_POST["username"]) || !isset($_POST["password"]) ) {
			echo("Form not completely filled out. <a href=\"register.php\">Please try again<a/>");
			die();
		} // We have all our input
		
		if(attempt_login($_POST["username"], $_POST["password"])) {
			echo("Successfully logged in!");
			$_SESSION["username"] = $_POST["username"];
			$_SESSION["loggedin"] = true;
		}
	} ?>
</body>
</html>