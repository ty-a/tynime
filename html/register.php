<?php
	define("TYNIME", 1);
	require_once("core.php");
	
	function verify_username_free($user) {
		global $dbHost, $dbUser, $dbPass, $dbName;
		
		$db = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
		if( $db->connect_error ) {
			echo("Failed to connect to Database");
			return false;
		}
		
		if(!($query = $db->prepare("SELECT * FROM users WHERE username = ?;"))) {
			echo("Failed to create query");
			return false;
		}
		
		if(!($query->bind_param( "s", $user ))) {
			echo("Failed to bind query params");
			return false;
		}
		
		if(!($query->execute())) {
			echo("Failed to execute query");
			return false;
		}
		
		$query->store_result();
		
		if($query->num_rows == 0) {
			return true;
		} else {
			echo("Username is already taken! Please try a different one!");
			return false;
		}
	}
	
	function add_user_to_db($user, $pass, $email) {
		global $dbHost, $dbUser, $dbPass, $dbName;
		
		$db = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
		if( $db->connect_error ) {
			echo("Failed to connect to Database");
			return false;
		}
		
		if(!($query = $db->prepare("INSERT INTO users(username, password, email) VALUES (?,?,?);"))) {
			echo("Failed to create query");
			return false;
		}
		$pass = password_hash($pass, PASSWORD_DEFAULT);
		if(!($query->bind_param( "sss", $user, $pass, $email ))) {
			echo("Failed to bind query params");
			return false;
		}
		
		if(!($query->execute())) {
			echo("Failed to execute query");
			return false;
		}
		
		return true;
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Register - tynime - Local Streaming Made Easy</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<?php addSiteReqs(); ?>
</head>
<body>
	<?php showSiteNavigation();
	if(empty($_POST)) {
	?>
	<div id="login-container" class="center">
		<form id="login-form" action="register.php" method="POST">
			<label for="username-text">Username</label>
			<input type="text" id="username-text" name="username" required></input>
			
			<label for="email-text">Email</label>
			<input type="text" id="email-text" name="email" required></input>
			
			<label for="password-text">Password</label>
			<input type="password" id="password-text" name="password" required></input>
			
			<label for="password-confirm-text">Confirm Password</label>
			<input type="password" id="password-confirm-text" name="password-confirm" required></input>
			
			<input type="submit" class="center" />
		</form>
	</div>
	<?php } else { // FORM WAS POSTED, YO
		// Only checking for those web browsers that do not support the required attribute or for users attempting to bot the form
		if(!isset($_POST["username"]) || !isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["password-confirm"]) ) {
			echo("Form not completely filled out. <a href=\"register.php\">Please try again<a/>");
			die();
		}
		
		// We have all our input
		
		if($_POST["password"] !== $_POST["password-confirm"]) {
			echo("Passwords do not match");
			die();
		}//passwords match!
		
		if(!verify_username_free($_POST["username"])) {
			die();
		} // username is available
		
		if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
			echo("Invalid email address");
			die();
		}// email is valid
		
		if(add_user_to_db($_POST["username"], $_POST["password"], $_POST["email"])) {
			echo("Successfully created account! <a href=\"login.php\">Please login!</a>");
		}
	} ?>
</body>
</html>