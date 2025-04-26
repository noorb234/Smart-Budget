<html>
<head>
    <title>Log Out</title>
    <link rel="stylesheet" type="text/css" href="styles.css"> 
</head>
<body id="loginBody"> 

<header id="top">
    <a href="Startup.php"><img src="logo.png" alt="Logo"></a> 
</header>

<main id="loginSection"> 
    <div class="login-form">
        <?php
		require_once 'config.php';
		
        session_start();
        
        // Checks if the user is logged in
        if (isset($_SESSION['username'])) {
			
			//Prepare statement to get user_id for user
			$username = $_SESSION['username'];
			$query = "SELECT user_id FROM users WHERE username = :username";
			$stmt = $pdo->prepare($query);
			$stmt->bindParam(':username', $username, PDO::PARAM_STR);
			$stmt->execute();
	
			//Sets the user_id
			$user_id = $stmt->fetchColumn();
			$stmt->closeCursor();
	
			//Prepare statement to get firstName for user
			$query_first_name = "SELECT firstName FROM users WHERE user_id = :user_id";
			$stmt_first_name = $pdo->prepare($query_first_name);
			$stmt_first_name->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$stmt_first_name->execute();

			// Fetch the user's first name
			$first_name = $stmt_first_name->fetchColumn();
			$stmt_first_name->closeCursor();
            
            destroy_session_and_data();
            
            echo "<h2>$first_name has successfully logged out.</h2>";
            echo "<p>Please <a href='Login.php'>Click Here</a> to log in again.</p>";
        } else {
            echo "<p>You were not logged in. Please <a href='Login.php'>Click Here</a> to log in.</p>";
        }
        
        // Function to destroy session cookies and data
        function destroy_session_and_data() {
            $_SESSION = array();
            setcookie(session_name(), '', time() - 2592000, '/');
            session_destroy();
        }
        ?>
    </div>
</main>

<footer class="footer">
    <div id="footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
</footer>

</body>
</html>