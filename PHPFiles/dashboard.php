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

if (isset($_SESSION['username']))
{
	//Prepare statement to get user_id for user
	$username = $_SESSION['username'];
	$query = "SELECT user_id FROM users WHERE username = :username";
	$stmt = $pdo->prepare($query);
	$stmt->bindParam(':username', $username, PDO::PARAM_STR);
	$stmt->execute();
	
	//Sets the user_id
	$user_id = $stmt->fetchColumn();
	$stmt->closeCursor();
		
	//Prepare statement to get the total monthly budget for the user
	$query_total_budget = "SELECT SUM(monthly_limit) AS total_budget from budget WHERE user_id = :user_id AND budget_month = :current_month";
	$stmt_budget = $pdo->prepare($query_total_budget);
	$current_month = date('Y-m-01');
    $stmt_budget->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_budget->bindParam(':current_month', $current_month, PDO::PARAM_STR);
    $stmt_budget->execute();
	
	//Fetches the total budget
	$total_budget = $stmt_budget->fetchColumn();
	$stmt_budget->closeCursor();
}
?>

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
    <h1 class = "WelcomeUser">Welcome, <?php echo htmlspecialchars($un); ?>!</h1>

    <main class = "mainBody">
        <nav class = "sidebar">
            <a class="sideTab" href ="dashboard.php">Profile</a>
            <a class="sideTab" href ="transactions.php">Transactions</a>
            <a class="sideTab" href = "viewReports.php">View Your Reports</a>
            <a class="sideTab" href = "BudgetScreen.php">Set A Budget</a>
        </nav>
        <div class = "dashboard">
            <h2>Monthly Expense Total: <?php echo isset($total_budget) ? '$' . number_format($total_budget, 2) : '$0.00'; ?></h2>
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