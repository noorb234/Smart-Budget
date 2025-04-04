<?php
include 'config.php'; // Include database connection file
try
	{
		$pdo = new PDO($attr, $user, $pass, $opts);
	}
	catch (PDOException $e)
	{
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}
	
	session_start();

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

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
}

// Fetch budget data from the database
// typically assuming table 'budget' with 'category', 'amount', and 'date'
$query = "SELECT c.category_name, SUM(b.current_spending) as total 
    FROM budget b
    JOIN category c ON b.category_id = c.category_id
	WHERE b.user_id = :user_id
    GROUP BY c.category_name";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$categories = [];
$amounts = [];
$colors = [];

// Define category colors
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
    $categories[] = $row['category_name'];  // Category names
    $amounts[] = $row['total'];        // Total amounts for each category
    $colors[] = isset($categoryColors[$row['category_name']]) ? $categoryColors[$row['category_name']] : '#000000';  // Default color if not found
}

$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Budget Reports</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <div class = "reports">
            <h2>Budget Report</h2>
            <canvas id="pieChart"></canvas>
            <canvas id="barChart"></canvas>
            
            <script>
                const categories = <?php echo json_encode($categories); ?>;
                const amounts = <?php echo json_encode($amounts); ?>;
                const colors = <?php echo json_encode($colors); ?>;
                
                // Pie Chart
                new Chart(document.getElementById('pieChart'), {
                    type: 'pie',
                    data: {
                        labels: categories,
                        datasets: [{
                            data: amounts,
                            backgroundColor: colors
                        }]
                    },
					options: {
						responsive: true,
						plugins: {
							tooltip: {
								callbacks: {
									// Custom label to format the tooltip as currency
									label: function(tooltipItem) {
										let amount = tooltipItem.raw; // Get the amount for the hovered slice
										amount = tooltipItem.parsed;
										return '$' + amount.toFixed(2); // Format as currency
									}
								}
							}
						}
					}
                });
                
                // Bar Chart
                new Chart(document.getElementById('barChart'), {
                    type: 'bar',
                    data: {
                        labels: categories,
                        datasets: [{
                            label: 'Expenses',
                            data: amounts,
                            backgroundColor: colors
                        }]
                    },
					options: {
						responsive: true,
						plugins: {
							tooltip: {
								callbacks: {
									// Custom label to format the tooltip as currency
									label: function(tooltipItem) {
										let amount = tooltipItem.parsed.y; // Get the amount for the hovered bar (vertical bar chart)
										return '$' + amount.toFixed(2); // Format as currency
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
<footer class = "footer">
    <div id = "footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
    
</footer>
</html>
