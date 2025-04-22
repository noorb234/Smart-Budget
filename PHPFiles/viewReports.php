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

// Get user_id from session
$query = "SELECT user_id FROM users WHERE username = :username";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':username', $un, PDO::PARAM_STR);
$stmt->execute();
$user_id = $stmt->fetchColumn();
$stmt->closeCursor();

// Get selected time filter
$timeframe = isset($_GET['timeframe']) ? $_GET['timeframe'] : 'monthly';

// Set date range based on filter
$endDate = date('Y-m-d H:i:s');
switch ($timeframe) {
    case 'weekly':
        $startDate = date('Y-m-d H:i:s', strtotime('-7 days'));
        break;
    case 'yearly':
        $startDate = date('Y-m-d H:i:s', strtotime('-1 year'));
        break;
    case 'monthly':
        $startDate = date('Y-m-d H:i:s', strtotime('-1 month'));
        break;
    case 'lastYear':
        // Set start and end dates for the entire last year
        $startDate = date('Y-m-d H:i:s', strtotime('first day of January last year'));
        $endDate = date('Y-m-d H:i:s', strtotime('last day of December last year'));
        break;
    case 'lastMonth':
        // Set start and end dates for the previous month
        $startDate = date('Y-m-d H:i:s', strtotime('first day of last month'));
        $endDate = date('Y-m-d H:i:s', strtotime('last day of last month'));
        break;
    default:
        $startDate = date('Y-m-d H:i:s', strtotime('-1 month'));
        break;
}

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

$categories = [];
$expenseAmounts = [];
$incomeAmounts = [];
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
    'TAXES' => '#D32F2F',
    'SAVINGS OR INVESTMENTS' => '#388E3C',
    'GIFTS AND DONATIONS' => '#1976D2',
    'LEGAL' => '#F44336'
];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $categories[] = $row['category_name'];
    $expenseAmounts[] = $row['expense_total'];
    $incomeAmounts[] = $row['income_total'];
    $colors[] = $categoryColors[$row['category_name']] ?? '#000000';
}

$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Budget Reports</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <a class="sideTab" href="goal.php">Goal Planning</a>
        </nav>

        <div class="reports">
            <h2>Budget Report</h2>

            <form method="get" style="margin-bottom: 20px;">
                <label for="timeframe">View by: </label>
                <select name="timeframe" id="timeframe" onchange="this.form.submit()">
                    <option value="weekly" <?php if ($timeframe === 'weekly') echo 'selected'; ?>>This Week</option>
                    <option value="monthly" <?php if ($timeframe === 'monthly') echo 'selected'; ?>>This Month</option>
                    <option value="yearly" <?php if ($timeframe === 'yearly') echo 'selected'; ?>>This Year</option>
                    <option value="lastYear" <?php if ($timeframe === 'lastYear') echo 'selected'; ?>>Last Year</option>
                    <option value="lastMonth" <?php if ($timeframe === 'lastMonth') echo 'selected'; ?>>Last Month</option>
                </select>
            </form>

            <canvas id="pieChart"></canvas>
            <canvas id="barChart"></canvas>

            <script>
                const categories = <?php echo json_encode($categories); ?>;
                const expenseAmounts = <?php echo json_encode($expenseAmounts); ?>;
                const incomeAmounts = <?php echo json_encode($incomeAmounts); ?>;
                const colors = <?php echo json_encode($colors); ?>;

            
                new Chart(document.getElementById('pieChart'), {
                    type: 'pie',
                    data: {
                        labels: categories,
                        datasets: [
                            {
                                label: 'Expenses',
                                data: expenseAmounts,
                                backgroundColor: colors
                            },
                            {
                                label: 'Income',
                                data: incomeAmounts,
                                backgroundColor: '#3498db' // Blue color for Income
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
                        },
                        legend: {
                            onClick: function (event, legendItem) {
                                const index = legendItem.datasetIndex;
                                const chart = this.chart;
                                const meta = chart.getDatasetMeta(index);
                                meta.hidden = !meta.hidden;  
                                chart.update();
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
                                label: 'Expenses',
                                data: expenseAmounts,
                                backgroundColor: colors
                            },
                            {
                                label: 'Income',
                                data: incomeAmounts,
                                backgroundColor: '#3498db' // Blue color for Income
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
                        },
                        legend: {
                            onClick: function (event, legendItem) {
                                const index = legendItem.datasetIndex;
                                const chart = this.chart;
                                const meta = chart.getDatasetMeta(index);
                                meta.hidden = !meta.hidden; 
                                chart.update();
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
