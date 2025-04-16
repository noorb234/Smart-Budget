<?php
require_once 'config.php';
	
	try
	{
		$pdo = new PDO($attr, $user, $pass, $opts);
	}
	catch (PDOException $e)
	{
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
<style>
    .goals {
        margin-top: 0px;
        display:  flex;
        /* background-color: aqua; */
        gap: 20px;
    }

    /***********************Side Bar*************************/ 
    .Goalsidebar{
        /* top: 300px; */
        width: 250px;
        height: 750px;
        margin-left: 25px;
        background-color: var(--light-blue);
        border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        transition: all var(--transition-speed) ease;
        font-family: sans-serif;
        font-size: 16px;
        font-weight: bold;
    }
    .GoalssideTab {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: var(--primary-color);
    font-weight: 600;
    font-size: 18px;
    padding: 12px 16px;
    border-radius: 8px;
    transition: all var(--transition-speed);
    }
    .GoalssideTab i {
        font-size: 20px;
    }
    
    .GoalssideTab:hover {
        background-color: var(--primary-color);
        color: white;
        transform: translateX(5px);
    }
    /***********************Side Bar*************************/

    /***********************Text Fields *************************/  
    #thingToBuyTextField,#PriceTextField,
    #timeFrameTextField,#AmountToSaveTextField,#FrecuencyTextField{
        align-items: center  ;
        width: 200px;
        height: 30px;
        padding: 10px;
        margin-bottom: 0px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        gap: 5px;
    }
    
     /***********************Text Fields *************************/  

    /*********************** Buttons *************************/  
     #GoalSubmitButton{

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

    /*********************** Buttons *************************/ 

    /************************** Labels **********************/ 
     .WelcomeGoalLabel{

        font-size: 30px;
        color:#0A599D;
        font-weight: bold;
        display: block;
        font-family:sans-serif;
        margin-top: 0px;
        margin-bottom: 3px;
     }
     .ItemGoalLabel{
        
        font-size: 13px;
        color:#0A599D;
        font-weight: bold;
        display: block;
        font-family:sans-serif;
        margin-top: 0px;
        margin-bottom: 3px;
        padding-left: 100px;
     }

    .NameGoalLabel,.PriceLabel,.GoalPriceLabel,.AmountToSaveLabel,.FrecuencyLabel{
        font-size: 18px;
        color:#0A599D;
        font-weight: bold;
        display: block;
        font-family:sans-serif;
        margin-top: 0px;
        margin-bottom: 3px;
        
    }
    .InformationLabel{
        font-size: 23px;
        color:#0A599D;
        font-weight: bold;
        display: block;
        text-align: center;
        font-family:sans-serif;
        margin-top:15px;
        margin-bottom: 0px;
        margin-left: 230px;
    }
    .AIResponseLabel{
        font-size: 15px;
        color:black;
        font-weight: bold;
        display: block;
        text-align: center;
        font-family:sans-serif;
        margin-top:30px;
        margin-bottom: 0px;
        margin-left: 200px;
    }
    .WelcomeUserGoalsLabel{
        padding: 40px 30px 10px 30px;
        margin-top: 100px;
        color:#0A599D;
        font-family: inter, sans-serif;
        /* font-size: 20px;
        color:#0A599D;
        font-weight: bold;
        display: block;
        font-family:sans-serif;
        margin-top:100px; */
        /* margin-bottom: 0px;
        margin-left: 200px; */ 
    }

    /************************** Labels **********************/

    /************************** Containers( div<>) & Form **********************/ 

    .GoalsForm{
        display: grid ;
        align-items: center  ;
        width: 540px;
        height: 500px;
        margin-bottom: 0px;
        gap: 5px;
        /* background-color: brown; */
    }

    .GoalFormDiv{
        display: grid;
        align-items: flex-start; 
        justify-items: flex-start;  
        width: 500px;
        height: 520px;
        margin-bottom: 0px;
        border-radius: 30px;
        gap: 10px;
        /* background-color: #E2F1FD; */
        padding-left: 40px;  
        
     }
     .AIresponseArea{
        display: flex;  
        flex-direction: column;
        justify-content: left;  
        width: 1000px;
        height: 700px;
        margin-bottom: 0px;
        border-radius: 30px;
        gap: 10px;
        /* background-color:rgb(223, 67, 28); */
        padding-left: 40px;  
     }

    .goalsContrainer {
    display: flex;  
    flex-direction: column;
    justify-content: left;
    align-items: left;  
    flex-grow: 1; 
    padding-left: 20px; 
    }
 /************************** Containers ( div<>) **********************/ 

</style>

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
        <label class="AIResponseLabel"></label>
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
</body>


</html>
