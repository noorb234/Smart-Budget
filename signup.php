<!DOCTYPE html>
<html>
<head>
    <title>SignUp</title>
    <script src="process_registration.js"></script>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body id ="signupBody">
    <header id = "top">
            <img src = "logo.png">
    </header>
    <main id = "signupSection">
        <!-------------------Signup Form-------------------->
       <form action = "process_signup.php" method = "post" onsubmit="return validate(this)" style="margin:auto; text-align:center;" class="signup-form">
        <h1 id = "signupheader">Sign Up</h1>
        <div class = "signup-form-top">
            <div class = "left">
                <div class = "input-box">
                    <label for = "firstName">First Name:</label><br>
                    <input type = "name" id = "name" name = "firstName" placeholder = "First Name"><br>
                </div>
                <div class = "input-box">
                    <label for = "email">Email:</label><br>
                    <input type = "email" id = "email" name = "email" placeholder = "Email"><br>
                </div>
            </div>
            <div class = "right">
                <div class = "input-box">
                    <label for = "lastName">Last Name:</label><br>
                    <input type = "name" id = "name" name = "lastName" placeholder = "Last Name"><br>
                </div>
                <div class = "input-box">
                    <label for = "phone">Phone:</label><br>
                    <input type = "tel" id = "phone" name ="phone" placeholder = "Phone"><br>
                </div>
            </div>
        </div>
            <div class = "signup-form-bottom">
                <div class = "input-box">
                    <label for = "createUsername">Create a username:</label><br>
                    <input type = "text" id = "createUsername" name = "username" placeholder = "Username"><br>
                </div>
                <div class = "input-box">
                    <label for = "createPassword">Create a password:</label><br>
                    <input type = "password" id = "createPassword" name = "password" placeholder = "Password"><br>
                </div>
                <div class = "input-box">
                    <label for = "confirmPassword">Confirm password:</label><br>
                    <input type = "password" id = "confirmPassword" name = "confirmPassword" placeholder = "Password"><br>
                </div>
                <div class = "input-box">
                    <label for = "question1">Security Question 1:</label><br>
                    <input type = "text" id = "question1" name = "security_question_1" placeholder = "DropDown"><br>
                </div>
                <div class = "input-box">
                    <label for = "answer1">Security Question 1 answer:</label><br>
                    <input type = "text" id = "answer1" name = "security_answer_1"placeholder = "Answer"><br>
                </div>
                <div class = "input-box">
                    <label for = "question2">Security Question 2:</label><br>
                    <input type = "text" id = "question2" name = "security_question_2" placeholder = "DropDown"><br>
                </div>
                <div class = "input-box">
                    <label for = "answer2">Security Question 2 answer:</label><br>
                    <input type = "text" id = "answer2" name = "security_answer_2" placeholder = "Answer"><br>
                </div>
            </div>
            <div id = "signupButtonSection">
                <button id = "signupButton" type = "submit">Create your Account</button>
    
                <div id = "HaveAccount">
                    <a href = "login.php">Already have an account? Click here to login!</a>
                </div>
            </div>
        </form>
        <!-------------------Signup Form-------------------->
    </main>
   
</body>
<footer class = "footer">
    <div id = "footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>

    </div>
</footer>
</html>