<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles2.css"> 
    <script src="include.js"></script>
</head>

<body onload="includeHeader()">
    <div include-header = "header.php"></div>
    
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
    <div class="dropdown">
        <button class="dropbtn">Menu</button>
        <div class="dropdown-content">
            <a href="#">Setting</a>
            <a href="#">Notifications</a>
            <a href="#">Theme</a>
            <a href="#">Log Out</a>
        </div>
    </div>
</body>
</html>
