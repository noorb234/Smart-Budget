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
	
	// Get the user's theme preference
	$query_theme = "SELECT preferences FROM users WHERE user_id = :user_id";
	$stmt_theme = $pdo->prepare($query_theme);
	$stmt_theme->bindParam(':user_id', $user_id, PDO::PARAM_INT);
	$stmt_theme->execute();
	$theme_preference = $stmt_theme->fetchColumn();
	$stmt_theme->closeCursor();
	
	//Prepare statement to get firstName for user
	$query_first_name = "SELECT firstName FROM users WHERE user_id = :user_id";
	$stmt_first_name = $pdo->prepare($query_first_name);
	$stmt_first_name->bindParam(':user_id', $user_id, PDO::PARAM_INT);
	$stmt_first_name->execute();

	// Fetch the user's first name
	$first_name = $stmt_first_name->fetchColumn();
	$stmt_first_name->closeCursor();
	
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
	
	// Prepare statement to get the total monthly limit for all categories for the current month
	$query_total_budget = "
		SELECT SUM(monthly_limit) 
		FROM budget 
		WHERE user_id = :user_id 
		AND budget_month = :current_month
		AND monthly_limit > 0";  // Ensure we only sum valid budgets

	$stmt_total_budget = $pdo->prepare($query_total_budget);
	$stmt_total_budget->bindParam(':user_id', $user_id, PDO::PARAM_INT);
	$stmt_total_budget->bindParam(':current_month', $current_month, PDO::PARAM_STR);
	$stmt_total_budget->execute();

	// Fetch total budget amount for all categories
	$total_budget = $stmt_total_budget->fetchColumn();
	$stmt_total_budget->closeCursor();
	
	$budget_warning = false;
	if ($total_budget > 0 && $total_spending >= 0.75 * $total_budget) {
		$budget_warning = true;
	}
	
	//Calulate percent of budget used
	$budget_used_percentage = 0;
	if ($total_budget > 0) {
		$budget_used_percentage = ($total_spending / $total_budget) * 100;
	}
	
	// Calculate remaining budget
	$remaining_budget = $total_budget - $total_spending;
	
	// Calculate how much over the budget they are
	$over_budget_amount = $total_spending - $total_budget;
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
	<script src="toggleTheme.js"></script>
	
</head>
 <!-- #region -->
<body class="<?php echo ($theme_preference === 'dark mode') ? 'dark-mode' : 'light-mode'; ?>">
    <?php include 'header.php'; ?></div>
    <h1 class = "WelcomeUser">Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>

    <main class = "mainBody">
        <nav class = "sidebar">
            <a class="sideTab" href ="dashboard.php">Profile</a>
            <a class="sideTab" href ="transactions.php">Transactions</a>
            <a class="sideTab" href = "viewReports.php">View Your Reports</a>
            <a class="sideTab" href = "BudgetScreen.php">Set A Budget</a>
			<a class="sideTab" href = "goal.php">Goal Planning</a>
        </nav>
        <div class="dashboard">
			<?php if ($user_budget === null || $user_budget == 0): ?>
				<p class="budget-warning">
					You haven't set a budget for this month. 
					<a href="BudgetScreen.php" class="set-budget-link">Set your budget now!</a>
				</p>
			<?php endif; ?>
            <h2 class="MonthlyExpensesLabel">Monthly Expense Total: <?php echo isset($total_spending) ? '$' . number_format($total_spending, 2) : '$0.00'; ?></h2>
            
			 <?php if ($budget_used_percentage > 100): ?>
				<p class="budget-warning">
					⚠️ You are over budget. You have spent $<?php echo number_format($over_budget_amount, 2); ?> over your monthly budget.
				</p>
			<?php elseif ($budget_used_percentage >= 75): ?>
				<p class="budget-warning">
					⚠️ You have used <?php echo number_format($budget_used_percentage, 2); ?>% of your monthly budget. 
					You have $<?php echo number_format($remaining_budget, 2); ?> remaining.
				</p>
			<?php endif; ?>
			
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
            <a class = "viewAllLink" href = "transactions.php">View All Transactions</a>
            <div class = "iconSection">
                <div class = "icon-item">
                    <a href = "transactions.php">
                        <i class = "fa-solid fa-square-plus"></i>
                        <p>Add an expense</p>
                    </a>
                </div>
                <div class = "icon-item">
                    <a href = "viewReports.php">
                        <i class = "fa fa-line-chart"></i>
                        <p>View your summary</p>
                    </a>
                </div>
                <div class = "icon-item">
                    <a href = "futurePlanning.php">
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