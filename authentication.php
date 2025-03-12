<html>


<head>
  <title>Login Authentication</title>
</head>

<fieldset>
	<label style="color:white">CSC325 Smart Budget -- Group 2</label>
</fieldset>
<body style="background-color:steelblue; color:snow">
<?php 
require_once 'dblogin.php';
	
	try
	{
		$pdo = new PDO($attr, $user, $pass, $opts);
	}
	catch (PDOException $e)
	{
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}
	//Checks if username and password are set
	if (isset($_POST['username']) && isset($_POST['password']))
	{
		//Sanitizes user inputs and stores it to variables
		$un_temp = htmlentities($_POST['username']);
		$pw_temp = htmlentities($_POST['password']);
		
		//Prepared statement to check if username is in system
		$stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
		$stmt->bindParam(1, $un_temp);
		$stmt->execute(); 

		//If user name is not found user is told and link is provided for them to return to login screen
		if (!$stmt->rowCount()) die("User not found.<br><br>Please return to login screen and try again.<br><br><a href='login.php'>Return to login page</a>");

		//Fetches user name password from database
		$row = $stmt->fetch();
		$un  = $row['username'];
		$pw  = $row['password'];
		
		//If user input for password matches, log in and go to main menu
		//if (password_verify($pw_temp, $pw))
		if ($pw_temp == $pw)
		{
			session_start();
			
			$_SESSION['username'] = $un;
			
			header('Location: dashboard.php');
		}
		//Else the user is told the password is invalid and must return to login screen to try again.
		else{ 
			die("Invalid username/password combination. Please return to login screen and try again.<br><br><a href='login.php'>Return to login page</a> $un_temp $pw_temp");
		}
	}
	else{
		echo "Please <a href='login.php'>Click Here</a> to log in.";
	}
?>

</body>
</html>