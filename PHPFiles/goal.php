<?php
require_once 'config.php';
	
try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
session_start();

$un = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $query_first_name = "SELECT firstName FROM users WHERE username = :username";
    $stmt_first_name = $pdo->prepare($query_first_name);
    $stmt_first_name->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt_first_name->execute();

    // Fetches the first name
    $first_name = $stmt_first_name->fetchColumn();
    $stmt_first_name->closeCursor();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles2.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <meta charset="UF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goals</title>
    <script src="include.js"></script>


</head>

<body onload="includeHeader()">
    <div include-header="header.php"></div> 

    <h1 class = "WelcomeUser">Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>

    <main class="goals">
        <nav class = "Goalsidebar">
            <a class="GoalssideTab" href ="dashboard.php">Profile</a>
            <a class="GoalssideTab" href ="transactions.php">Transactions</a>
            <a class="GoalssideTab" href = "viewReports.php">View Your Reports</a>
            <a class="GoalssideTab" href = "BudgetScreen.php">Set A Budget</a>
            <a class="GoalssideTab" href = "goal.php">Goal Planning</a>
        </nav>
        
        <div class="goalsContrainer">
        
            <form class="GoalsForm" id="goalForm">

                <div class="GoalFormDiv">

                    <label class="WelcomeGoalLabel"><b>Welcome to the Goal Section</b></label>
                    <label class="ItemGoalLabel">Provide a item that you would like to buy </label>

                    <label class="NameGoalLabel">Name</label> 
                    <input type="text" id="thingToBuyTextField" placeholder="Enter name">

                    <label class="PriceLabel">Price</label>
                    <input type="text" id="PriceTextField" placeholder="Enter price">

                    <label class="AmountToSaveLabel">Amount to Save</label>
                    <input type="text" id="AmountToSaveTextField" placeholder="Ex. $50">

                    <label class="GoalPriceLabel">Time Range</label>
                    <input type="text" id="timeFrameTextField" placeholder="Ex. 5 months">

                    <label class="FrecuencyLabel">Frecuency</label>
                    <input type="text" id="FrecuencyTextField" placeholder="Ex. weekly">

                    <button id="GoalSubmitButton" type="button">Submit</button>
                </div>

            </form> 
                
        </div>

        <div class="AIresponseArea">
        <label class="InformationLabel">Provide information about an Item and tips will be given</label>
        <label class="AIResponseLabel"> </label>
        </div>
        


    </main>
 
 

    <footer class="footer">
        <div id="footerSection">
            <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
        </div>
    </footer>
    <script>
document.getElementById("GoalSubmitButton").addEventListener("click", async function () {
    // Get values from the input fields
    const item = document.getElementById("thingToBuyTextField").value;
    const price = document.getElementById("PriceTextField").value;
    const amount = document.getElementById("AmountToSaveTextField").value;
    const time = document.getElementById("timeFrameTextField").value;
    const frequency = document.getElementById("FrecuencyTextField").value;

    const responseLabel = document.querySelector(".AIResponseLabel");

    // Validation
    if (!item || !price || !amount || !time || !frequency) {
        responseLabel.innerText = "All fields are required.";
        return;
    }

    if (isNaN(price) || isNaN(amount)) {
        responseLabel.innerText = "Price and Amount to Save must be valid numbers.";
        return;
    }

    responseLabel.innerText = "Generating advice...";

    try {
        // Sending data to goal_api.php (backend)
        const response = await fetch("goal_api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ item, price, amount, time, frequency })  // Send input data as JSON
        });

        // Parse the JSON response from the server
        const data = await response.json();
        
        // Display the AI response in the label
        responseLabel.innerText = data.reply || "No advice was generated."; // Display AI reply or default message
    } catch (error) {
        console.error(error);
        responseLabel.innerText = "An error occurred while contacting the server.";  // Error handling
    }
});
    </script>
</body

