<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    <script src="include.js"></script>
</head>

<body onload="includeHeader()">
    <div include-header = "header.php"></div>
    
    <main class = "budgetBody">
    <div class="sidebar1">
        <a href="profile.php">Profile</a>
        <a href="transactions.php">Transactions</a>
        <a href="reports.php">View your Reports</a>
        <a href="budget.php">Set a Budget</a>
    </div>

    <button class="button1">Edit Budget</button>
    <button class="button2">Save Budget</button>
    <label class="welcome-label"><b>Welcome:  "user"</b></label><br>
    <label class="Category-label"><b>Category: </b></label><br>
    <label class="your-budget"><b>Your Budget for the Month: $10</b></label><br>
    </main>
</body>

<footer class = "footer">
    <div id = "footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
</footer>
</html>
