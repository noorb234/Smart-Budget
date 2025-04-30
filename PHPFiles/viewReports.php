<?php
include 'config.php'; // Include database connection file

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

session_start();

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$un = $_SESSION['username'];
$query_first_name = "SELECT firstName FROM users WHERE username = :username";
$stmt_first_name = $pdo->prepare($query_first_name);
$stmt_first_name->bindParam(':username', $un, PDO::PARAM_STR);
$stmt_first_name->execute();
$first_name = $stmt_first_name->fetchColumn();
$stmt_first_name->closeCursor();

// Get user_id from session or DB
$query = "SELECT user_id FROM users WHERE username = :username";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':username', $un, PDO::PARAM_STR);
$stmt->execute();
$user_id = $stmt->fetchColumn();
$stmt->closeCursor();

// Get the user's theme preference
	$query_theme = "SELECT preferences FROM users WHERE user_id = :user_id";
	$stmt_theme = $pdo->prepare($query_theme);
	$stmt_theme->bindParam(':user_id', $user_id, PDO::PARAM_INT);
	$stmt_theme->execute();
	$theme_preference = $stmt_theme->fetchColumn();
	$stmt_theme->closeCursor();

// Get selected time filter
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] . ' 00:00:00' : date('Y-m-01 00:00:00');
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] . ' 23:59:59' : date('Y-m-d 23:59:59');

// Fetch budget data with date filter
$query = "SELECT c.category_name, 
                 SUM(CASE WHEN t.transaction_type = 'EXPENSE' THEN t.transaction_amount ELSE 0 END) AS expense_total,
                 SUM(CASE WHEN t.transaction_type = 'INCOME' THEN t.transaction_amount ELSE 0 END) AS income_total
          FROM transaction t
          JOIN category c ON t.category_id = c.category_id
          WHERE t.user_id = :user_id
            AND t.transaction_date BETWEEN :startDate AND :endDate
          GROUP BY c.category_name";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':startDate', $startDate);
$stmt->bindParam(':endDate', $endDate);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories = [];
$expenseAmounts = [];
$incomeAmounts = [];
$budgetLimits = [];
$colors = [];

$categoryColors = [
    'HOUSING' => '#FF5733',
    'TRANSPORTATION' => '#33FF57',
    'INSURANCE' => '#3357FF',
    'FOOD' => '#F39C12',
    'PETS' => '#8E44AD',
    'PERSONAL CARE' => '#BDC3C7',
    'ENTERTAINMENT' => '#1F77B4',
    'LOANS' => '#FF9F00',
    'TAXES' => '#C2185B',
    'SAVINGS OR INVESTMENTS' => '#388E3C',
    'GIFTS AND DONATIONS' => '#1976D2',
    'LEGAL' => '#9b59b6'
];

// Populate arrays with result data
foreach ($results as $row) {
    $category = $row['category_name'];
    $categories[] = $category;
    $expenseAmounts[] = (float) $row['expense_total'];
    $incomeAmounts[] = (float) $row['income_total'];
    $colors[] = $categoryColors[$category] ?? '#7f8c8d'; // fallback color

    // Get budget limit per category
    $budgetQuery = "SELECT monthly_limit 
                    FROM budget b 
                    JOIN category c ON b.category_id = c.category_id 
                    WHERE b.user_id = :user_id 
                      AND c.category_name = :category_name 
                      AND DATE_FORMAT(b.budget_month, '%Y-%m') = :month
                    LIMIT 1";
    $budgetStmt = $pdo->prepare($budgetQuery);
    $budgetStmt->execute([
        ':user_id' => $user_id,
        ':category_name' => $category,
        ':month' => date('Y-m', strtotime($startDate))
    ]);
    $budgetLimit = $budgetStmt->fetchColumn();
    $budgetLimits[] = $budgetLimit ?: 0;
}

$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reports</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="include.js"></script>
	<script src="toggleTheme.js"></script>
</head>

<body class="<?php echo ($theme_preference === 'dark mode') ? 'dark-mode' : 'light-mode'; ?>">
    <?php include 'header.php'; ?></div>
    <h1 class = "WelcomeUser">Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>

    <main class="mainBody">
        <nav class="sidebar">
            <a class="sideTab" href="dashboard.php">Profile</a>
            <a class="sideTab" href="transactions.php">Transactions</a>
            <a class="sideTab" href="viewReports.php">View Your Reports</a>
            <a class="sideTab" href="BudgetScreen.php">Set A Budget</a>
            <a class="GoalssideTab" href = "goal.php">BudgetWise AI</a>
        </nav>

        <div class="reports">
            <h2>View Your Spending</h2>

            <form method="get" style="margin-bottom: 20px;">
                <label for="start">Start Date:</label>
                <input type="date" name="startDate" value="<?php echo isset($_GET['startDate']) ? $_GET['startDate'] : ''; ?>">
                <label for="end">End Date:</label>
                <input type="date" name="endDate" value="<?php echo isset($_GET['endDate']) ? $_GET['endDate'] : ''; ?>">
                <button type="submit">Filter</button>
            </form>

            <canvas id="pieChart"></canvas>
            <canvas id="barChart"></canvas>

            <script>
                const categories = <?php echo json_encode($categories); ?>;
                const expenseAmounts = <?php echo json_encode($expenseAmounts); ?>;
                const incomeAmounts = <?php echo json_encode($incomeAmounts); ?>;
                const colors = <?php echo json_encode($colors); ?>;
                const budgetLimits = <?php echo json_encode($budgetLimits); ?>;

                new Chart(document.getElementById('pieChart'), {
                    type: 'pie',
                    data: {
                        labels: categories,
                        datasets: [
                            {
                                label: 'Expenses',
                                data: expenseAmounts,
                                backgroundColor: colors
                             }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function (tooltipItem) {
                                        let amount = tooltipItem.parsed;
                                        return '$' + amount.toFixed(2);
                                    }
                                }
                            }
                        }
                    }
                });

                new Chart(document.getElementById('barChart'), {
                    type: 'bar',
                    data: {
                        labels: categories,
                        datasets: [
                            {
                                label: 'Actual Spending',
                                data: expenseAmounts,
                                backgroundColor: '#F44336'
                            },
                            {
                                label: 'Planned Budget',
                                data: budgetLimits,
                                backgroundColor: '#1976D2'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function (tooltipItem) {
                                        let amount = tooltipItem.parsed.y;
                                        return '$' + amount.toFixed(2);
                                    }
                                }
                            }
                        }
                    }
                });
            </script>
        </div>
    </main>
</body>

<footer class="footer">
    <div id="footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
</footer>
</html>
