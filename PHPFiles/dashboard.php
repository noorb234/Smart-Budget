<?php
require_once 'config.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
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
	
	// Prepare statement to get the user's budget for the current month
    $query_budget = "SELECT monthly_limit FROM budget WHERE user_id = :user_id AND budget_month = :current_month";
    $stmt_budget = $pdo->prepare($query_budget);
    $current_month = date('Y-m-01');
    $stmt_budget->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_budget->bindParam(':current_month', $current_month, PDO::PARAM_STR);
    $stmt_budget->execute();

    // Fetches the user's budget amount
    $user_budget = $stmt_budget->fetchColumn();
    $stmt_budget->closeCursor();
		
	//Prepare statement to get the total current spending for the user
	$query_total_spending = "SELECT SUM(current_spending) AS total_spending from budget WHERE user_id = :user_id AND budget_month = :current_month";
	$stmt_spending = $pdo->prepare($query_total_spending);
	$current_month = date('Y-m-01');
    $stmt_spending->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_spending->bindParam(':current_month', $current_month, PDO::PARAM_STR);
    $stmt_spending->execute();
	
	//Fetches the total spending
	$total_spending = $stmt_spending->fetchColumn();
	$stmt_spending->closeCursor();
	
	// Fetch transactions for the user
	$query = "SELECT t.transaction_id, t.user_id, t.category_id, t.transaction_amount, 
	t.transaction_date, t.transaction_type, t.note, t.is_recurring, c.category_name
	FROM transaction t
    JOIN category c ON t.category_id = c.category_id
    WHERE t.user_id = :user_id
    ORDER BY t.transaction_date DESC
	LIMIT 5";
	$stmt = $pdo->prepare($query);
	$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
	$stmt->execute();
	$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta charset="UTF-8">
    <script src="include.js"></script>
</head>
<body onload="includeHeader()">
    <div include-header="header.php"></div>
    <h1 class="WelcomeUser">Welcome, <?php echo htmlspecialchars($un); ?>!</h1>
    <main class="mainBody">
        <nav class="sidebar">
            <a class="sideTab" href="dashboard.php">Profile</a>
            <a class="sideTab" href="transactions.php">Transactions</a>
            <a class="sideTab" href="viewReports.php">View Your Reports</a>
            <a class="sideTab" href="BudgetScreen.php">Set A Budget</a>
        </nav>
        <div class = "dashboard">
			<!-- Check if the budget is set for the current month -->
            <?php if ($user_budget === null || $user_budget == 0): ?>
				<p class="budget-warning">
					You haven't set a budget for this month. 
					<a href="BudgetScreen.php" class="set-budget-link">Set your budget now!</a>
				</p>
			<?php endif; ?>
            <h2>Monthly Expense Total: <?php echo isset($total_spending) ? '$' . number_format($total_spending, 2) : '$0.00'; ?></h2>
            <!-----Inserting total------>
            <h2>Recent Transactions:</h2>
            <table>
				<tr>
					<th>Date</th>
					<th>Amount</th>
					<th>Type</th>
					<th>Category</th>
					<th>Note</th>
				</tr>
				<?php foreach ($transactions as $transaction): ?>
					<tr>
						<td><?php echo htmlspecialchars($transaction['transaction_date']); ?></td>
						<td><?php echo htmlspecialchars($transaction['transaction_amount']); ?></td>
						<td><?php echo htmlspecialchars($transaction['transaction_type']); ?></td>
						<td><?php echo htmlspecialchars($transaction['category_name']); ?></td>
						<td><?php echo htmlspecialchars($transaction['note']); ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
            <a class = "viewAllLink" href = "#">View All Transactions</a>
            <div class = "iconSection">
                <div class = "icon-item">
                    <a href = "#">
                        <i class = "fa-solid fa-square-plus"></i>
                        <p>Add an expense</p>
                    </a>
                </div>
                <div class="icon-item">
                    <a href="#">
                        <i class="fa fa-line-chart"></i>
                        <p>View your summary</p>
                    </a>
                </div>
                <div class="icon-item">
                    <a href="#">
                        <i class="fa-solid fa-calculator"></i>
                        <p>What if Calculator?</p>
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
<footer class="footer">
    <div id="footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
</footer>
</html>
