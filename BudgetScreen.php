<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .welcome-label {
            color: #000000; 
            font-size: 15px; 
            margin-top: 175px; 
            position: absolute; 
            left: 10px; 
        }
        .your-budget{
            color: #000000; 
            font-size: 30px; 
            margin-top: 225px; 
            position: absolute; 
            left: 280px;
        }
        .Category-label{
            color: #000000; 
            font-size: 20px; 
            position: absolute; 
            top: 500px;
            left:280px;
        }
        .sidebarTop{
            display: flex; 
            align-items: center; 
            background-color: #7ce3fd;
            gap: 300px;
            padding: 25px;
            width: 70%; 
            height: 80px; 
            position: absolute;
            top: 0px;
            left: 300px;
        }
        .sidebar1 {
            position: absolute;
            top: 225px; 
            left: 0;     
            width: 200px; 
            background-color: #7ce3fd; 
            padding: 20px; 
            height: 100vh;  
        }

        .sidebar1 a {
            display: block; 
            text-decoration: none;
            color: #1960b1;
            font-size: 18px;
            margin: 100px 0; 
            padding: 10px;
            text-align: center;
        }
        .sidebar1 a:hover {
            background-color: #2da3b8; 
        }
        .sidebar img {
            width: 180px; 
            height: 100px;
            position: absolute;
            top: 10px;
            right: 1700px;
        }
        .dropbtn {
            background-color: #008CBA;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            position: absolute;
            top: 5px;
            left:1685px;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
            z-index: 1;
            position: absolute;
            top: 50px;
            left:1700px;
        }

        .dropdown-content a {
            color: black;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #ddd;
        }

      
        .button1 {
            background-color: #008CBA;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            position: absolute;
            top: 800px; 
            left: 450px;
        }
        
        .button2 {
            background-color: #008CBA;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            position: absolute;
            top: 800px; 
            left: 600px;
        }

        .button1:hover {
            background-color: #006494;
        }

        .button2:hover {
            background-color: #006494;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <img src="C:\xampp\htdocs\Smart-Budget\logo.png" alt="Profile Image">
    </div>
    <div class="sidebar1">
        <a href="profile.php">Profile</a>
        <a href="transactions.php">Transactions</a>
        <a href="reports.php">View your Reports</a>
        <a href="budget.php">Set a Budget</a>
    </div>
    <div class="sidebarTop">
        <a href="Startup.php">Profile</a>
        <a href="dashboard.php">Transactions</a>
        <a href="BudgetScreen1.html">View your Reports</a>
        <a href=" ">Set a Budget</a> <!-- File name will be needed -->
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
