<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta charset="UTF-8">
    <script src="include.js"></script>
</head>

<body onload="includeHeader()">
    <div include-header = "header.php"></div>
    <h1 class = "WelcomeUser">Welcome User!</h1>

    <main class = "mainBody">
        <nav class = "sidebar">
            <a class="sideTab" href ="dashboard.php">Profile</a>
            <a class="sideTab" href ="transactions.php">Transactions</a>
            <a class="sideTab" href = "viewReports.php">View Your Reports</a>
            <a class="sideTab" href = "BudgetScreen.php">Set A Budget</a>
        </nav>
        <div class = "dashboard">
            <h2>Monthly Expense Total:</h2>
            <!-----Inserting total------>
            <h2>Recent Transactions:</h2>
            <!-------Insert transactions----->
            <a class = "viewAllLink" href = "#">View All Transactions</a>
            <div class = "iconSection">
                <div class = "icon-item">
                    <a href = "#">
                        <i class = "fa-solid fa-square-plus"></i>
                        <p>Add an expense</p>
                    </a>
                </div>
                <div class = "icon-item">
                    <a href = "#">
                        <i class = "fa fa-line-chart"></i>
                        <p>View your summary</p>
                    </a>
                </div>
                <div class = "icon-item">
                    <a href = "#">
                        <i class = "fa-solid fa-calculator"></i>
                        <p>What if Calculator?</p>
                    </a>
                </div>

            </div>
        </div>
</main>

</body>
<footer class = "footer">
    <div id = "footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
    
</footer>

</html>