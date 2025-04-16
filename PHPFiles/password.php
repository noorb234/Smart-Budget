<!DOCTYPE html>
<?php
require_once 'config.php';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" type="text/css" href="styles3.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="resetPassword.css">
</head>
<body id="forgotPasswordBody">

<header id="passwordTop">
    <a href="Startup.php">
        <img src="logo.png" alt="Logo">
    </a>
</header>

<main id="ForgotPassword">
    <form id="findUsernameForm" onsubmit="lookupUsername(event)">
        <div class="findUsernamediv">
            <label class="passwordUsernameLabel">Username:</label>
            <input required type="text" id="passwordUsername" placeholder="Username">
            <button id="findUsername" type="submit">Look up Username</button>
        </div>
    </form>

    <form id="formPassword" style="display: none;" onsubmit="resetPassword(event)">
        <div class="resetPasswordform">
            <label class="firstQuestionLabel" id="firstQuestionLabel">First Question goes here</label>
			<input required type="text" id="firstAnswerField" placeholder="Answer">

			<label class="secondQuestionLabel" id="secondQuestionLabel">Second Question goes here</label>
			<input required type="text" id="secondAnswerField" placeholder="Answer">

            <label class="ResetPasswordLabel">Reset Password:</label>
            <input required type="text" id="ResetPasswordLabelField" placeholder="New Password">
            <button id="resetPassword" type="button">Reset Password</button>
        </div>
    </form>
</main>

<footer class="footer">
    <div id="footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
</footer>

<!-- Link to external JavaScript file -->
<script src="resetPassword.js"></script>

</body>
</html>
