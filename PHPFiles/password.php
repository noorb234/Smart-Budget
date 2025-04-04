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
            width: 150px;
        }

        /* Main form styling */
        #ForgotPassword {
            width: 400px;
            height: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background-color: #E2F1FD;
        }

        .resetPasswordform {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .resetPasswordform div {
            margin-bottom: 15px;
        }

        /**************************** TextFields ************************************/
        #passwordUsername,
        #firstAnswerLabel,
        #secondAnswerLabel,
        #thirdAnswerLabel {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        /**************************** Labels ************************************/
        .passwordUsernameLabel,
        .firstQuestionLabel,
        .secondQuestionLabel,
        .thirdQuestionLabel {
            font-weight: bold;
        }

        /**************************** Button ************************************/
        #resetPassword {
            border-radius: 30px;
            background-color: #0A599D;
            width: 40%;
            height: 40px;
            margin-top: 20px;
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
        }

        /************************** Dropdown Styles *************************/
        .dropdownQuestions,
        .dropdownQuestions2,
        .dropdownQuestions3 {
            position: relative;
            width: 100%;
        }

        .dropdownQuestionsList,
        .dropdownQuestionsList2,
        .dropdownQuestionsList3 {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background-color: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            border-radius: 4px;
            display: none;
        }

        .dropdownQuestionsList a,
        .dropdownQuestionsList2 a,
        .dropdownQuestionsList3 a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #ddd;
        }

        .dropdownQuestionsList a:hover,
        .dropdownQuestionsList2 a:hover,
        .dropdownQuestionsList3 a:hover {
            background-color: #a2a1a3;
        }

        .questionButton,
        .questionButton2,
        .questionButton3{
            width: 100%;
            padding: 10px;
            background-color: #0A599D;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .questionButton:hover {
            background-color: #0056b3;
        }

        /* Show dropdown when active */
        .active .dropdownQuestionsList,
        .active .dropdownQuestionsList2,
        .active .dropdownQuestionsList3 {
            display: block;
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
        <form id="formPassword" method="post" action="">
            <div class="resetPasswordform">
                <div>
                    <label class="passwordUsernameLabel">Username:</label>
                    <input required type="text" id="passwordUsername" placeholder="Username">
                </div>

                <!-- First Question Dropdown -->
                <div class="dropdownQuestions">
                    <button type="button" class="questionButton">Select First Question</button>
                    <div class="dropdownQuestionsList">
                        <a href="#">First Question</a>
                        <a href="#">Second Question</a>
                        <a href="#">Third Question</a>
                    </div>
                </div>

                <div>
                    <label class="firstQuestionLabel">First Question Answer:</label>
                    <input required type="text" id="firstAnswerLabel" placeholder="Answer">
                </div>

                <!-- Second Question Dropdown -->
                <div class="dropdownQuestions2">
                    <button type="button" class="questionButton2">Select Second Question</button>
                    <div class="dropdownQuestionsList2">
                        <a href="#">First Question</a>
                        <a href="#">Second Question</a>
                        <a href="#">Third Question</a>
                    </div>
                </div>

                <div>
                    <label class="secondQuestionLabel">Second Question Answer:</label>
                    <input required type="text" id="secondAnswerLabel" placeholder="Answer">
                </div>

                <!-- Third Question Dropdown -->
                <div class="dropdownQuestions3">
                    <button type="button" class="questionButton3">Select Third Question</button>
                    <div class="dropdownQuestionsList3">
                        <a href="#">First Question</a>
                        <a href="#">Second Question</a>
                        <a href="#">Third Question</a>
                    </div>
                </div>

                <div>
                    <label class="thirdQuestionLabel">Third Question Answer:</label>
                    <input required type="text" id="thirdAnswerLabel" placeholder="Answer">
                </div>

                <button id="resetPassword" type="submit">Reset Password</button>
            </div>
        </form>
    </main>

    <footer class="footer">
        <div id="footerSection">
            <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
        </div>
    </footer>

    <script>
        // JavaScript to toggle the dropdown for each question
        const questionButtons = document.querySelectorAll('.questionButton, .questionButton2, .questionButton3');
        questionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const dropdown = this.parentElement;
                dropdown.classList.toggle('active');
            });
        });
    </script>

</body>
</html>
