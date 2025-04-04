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
    $query = "SELECT user_id FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user_id = $stmt->fetchColumn();
    $stmt->closeCursor();
    
    $query_budget = "SELECT SUM(monthly_limit) AS total_budget FROM budget WHERE user_id = :user_id AND budget_month = :current_month";
    $stmt_budget = $pdo->prepare($query_budget);
    $current_month = date('Y-m-01');
    $stmt_budget->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_budget->bindParam(':current_month', $current_month, PDO::PARAM_STR);
    $stmt_budget->execute();
    $total_budget = $stmt_budget->fetchColumn();
    $stmt_budget->closeCursor();
}

$planned_purchase = 3500;
$worst_case_spending = 4005;
$best_case_spending = 3250;
$budget_status = ($worst_case_spending > $total_budget) ? 'over' : 'within';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>What If? Calculator</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="include.js"></script>
</head>
<body onload="includeHeader()">
    <div include-header="header.php"></div>
    <h1 class="WelcomeUser">Welcome <?php echo htmlspecialchars($un); ?>!</h1>
    <main class="mainBody">
        <nav class="sidebar">
            <a class="sideTab" href="dashboard.php">Dashboard</a>
            <a class="sideTab" href="transactions.php">Transactions</a>
            <a class="sideTab" href="viewReports.php">View Reports</a>
            <a class="sideTab" href="BudgetScreen.php">Set A Budget</a>
        </nav>
        
        <div class="calculator">
            <h2>What If? Calculator</h2>
            <p><b>Planned Purchase:</b> Car ($<?php echo number_format($planned_purchase, 2); ?>)</p>
            <p><b>Best Case Scenario:</b> October, you spent $<?php echo number_format($best_case_spending, 2); ?>. <span style="color: green;">This fits into your budget</span></p>
            <p><b>Worst Case Scenario:</b> You spent $<?php echo number_format($worst_case_spending, 2); ?>. <span style="color: <?php echo ($budget_status == 'over') ? 'red' : 'green'; ?>;">This <?php echo ($budget_status == 'over') ? 'puts you over budget' : 'fits into your budget'; ?></span></p>
            
            <h3>Chart:</h3>
            <div id="chart-container">
                <canvas id="budgetChart"></canvas>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('budgetChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Planned Budget', 'Actual Spending', 'Future Purchase'],
                datasets: [{
                    label: 'Monthly Budget Comparison',
                    data: [<?php echo $total_budget; ?>, <?php echo $worst_case_spending; ?>, <?php echo $planned_purchase; ?>],
                    backgroundColor: ['blue', 'red', 'orange']
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
<footer class="footer">
    <div id="footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>&copy; 2025 SmartBudget</p>
    </div>
</footer>
</html>
