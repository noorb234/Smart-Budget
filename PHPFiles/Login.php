<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body id ="loginBody">
    <header id = "top">
        <a href = "Startup.php"> <img src = "logo.png" ></a>
    </header>
    <main id = "loginSection">
        <!-------------------Login Form-------------------->
        <form method="post" action = "authentication.php" >
        <div class = "login-form">
            <div>
                <label for = "username">Username:</label><br>
                <input required type = "text" id = "username" placeholder = "Username" name = "username"><br>
            </div>
            <div>
                <label for = "password">Password:</label><br>
                <input required type = "password" id = "password" placeholder = "Password" name = "password"><br>
            </div>
            <button id = "loginButton" type = "submit">Login </button>
        </form>
        <!-------------------Login Form-------------------->

            <p id = "noAccount"><a href = "signup.php">Don't have an account? Click here to get started!</a></p>
        </div>
    </main>
   
</body>
<footer class = "footer">
    <div id = "footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>

    </div>
</footer>
</html>