<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" type="text/css" href="styles3.css">
    <link rel="stylesheet" type="text/css" href="styles.css">

    <style>
        /* Styling for the header */
        #passwordTop {
            text-align: center;
            padding: 20px;
        }
        #passwordTop img {
            width: 200px;
        }

        /* Main form styling */
        #ForgotPassword {
            width: 500px;
            height: 100%;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background-color: #E2F1FD;
        }

        .findUsernamediv{
            display: grid ;
            align-items: center  ;
            justify-content: center;
            width: 500px;
            height: 150px;
            margin-bottom: 0px;
            display: flex;
            flex-direction: column;
            gap: 20px;

        }

        .resetPasswordform {
            display: grid ;
            align-items: center  ;
            justify-content: center;
            width: 500px;
            height: 500px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .resetPasswordform div {
            margin-bottom: 15px;
        }

        /**************************** TextFields ************************************/
        #passwordUsername{
            display: grid ;
            align-items: center  ;
            width: 70%;
            padding: 10px;
            margin-bottom: 0px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        #firstAnswerField,
        #secondAnswerField {
            display: grid ;
            align-items: center  ;
            width: 70%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        #ResetPasswordLabelField{
            display: grid ;
            align-items: center  ;
            width: 70%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        /**************************** Labels ************************************/
        .passwordUsernameLabel{
            font-size: 18px;
            color:#0A599D;
            font-weight: bold;
            display: block;
            font-family:sans-serif;
            margin-top: 10px;
            margin-bottom: 3px;
        }
        .firstQuestionLabel{
            font-size: 18px;
            color:#0A599D;
            font-weight: bold;
            display: block;
            font-family:sans-serif;
            margin-top: 10px;
            margin-bottom: 3px;
        }
        .secondQuestionLabel{
            font-size: 18px;
            color:#0A599D;
            font-weight: bold;
            display: block;
            font-family:sans-serif;
            margin-top: 10px;
            margin-bottom: 3px;
        }
        .ResetPasswordLabel{
            font-size: 18px;
            color:#0A599D;
            font-weight: bold;
            display: block;
            font-family:sans-serif;
            margin-top: 10px;
            margin-bottom: 3px;
        }

        /**************************** Button ************************************/
        #resetPassword {
            border-radius: 30px;
            background-color: #0A599D;
            width: 40%;
            height: 40px;
            margin-top: 0px;
            border: none;
            cursor: pointer;
            color: whitesmoke;
            text-decoration: none;
            font-family: sans-serif;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        #resetPassword:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        #findUsername{
            border-radius: 30px;
            background-color: #0A599D;
            width: 40%;
            height: 40px;
            margin-top:0px;
            margin-bottom: 0px;
            border: none;
            cursor: pointer;
            color: whitesmoke;
            text-decoration: none;
            font-family: sans-serif;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        #findUsername:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

    </style>
</head>
<body id="forgotPasswordBody">

    <header id="passwordTop">
        <a href="Startup.php">
            <img src="logo.png" alt="Logo">
        </a>
    </header>

    <main id="ForgotPassword">
        <form id="findUsernameForm" method="post" action="">
            
                <div  class="findUsernamediv">
                    <label class="passwordUsernameLabel">Username:</label>
                    <input required type="text" id="passwordUsername" placeholder="Username">
                    <button id="findUsername" type="submit">Look up Username</button>

                </div>

        </form>
        <form id="formPassword" method="post" action="">
            <div class="resetPasswordform">
                <label class="firstQuestionLabel">First Question goes here</label>
                <input required type="text" id="firstAnswerField" placeholder="Answer">

                <label class="secondQuestionLabel">Second Question goes here</label>
                <input required type="text" id="secondAnswerField" placeholder="Answer">

                <label class="ResetPasswordLabel">Reset Password:</label>
                <input required type="text" id="ResetPasswordLabelField" placeholder="New Password">
                <button id="resetPassword" type="submit">Reset Password</button>
              
            </div>
        </form>
    </main>

    <footer class="footer">
        <div id="footerSection">
            <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
        </div>
    </footer>

</body>
</html>
