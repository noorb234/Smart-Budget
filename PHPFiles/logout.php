<html>

<head>
  <title>Log Out</title>
</head>

<body>
<?php
	session_start();
	
	//Checks if user is logged in
	if (isset($_SESSION['username']))
	{
		$username = $_SESSION['username'];
		
		destroy_session_and_data();
		
		echo "<br>";
		echo htmlspecialchars("$username has successfully logged out.");
		echo "<br><br>Please <a href='Login.php'>Click Here</a> to log in.";
	}
	else{
		echo "<br><br>Please <a href='Login.php'>Click Here</a> to log in.";
	}
	
	//Function to destroy session cookies and data
	function destroy_session_and_data()
	{
		$_SESSION = array();
		setcookie(session_name(), '', time() - 2592000, '/');
		session_destroy();
	}

?>
</body>
</html>