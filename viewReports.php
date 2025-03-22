<?php
include 'db_connection.php'; // Include database connection file

// Fetch budget data from the database
typically assuming table 'budget' with 'category', 'amount', and 'date'
$query = "SELECT category, SUM(amount) as total FROM budget GROUP BY category";
$result = mysqli_query($conn, $query);

$categories = [];
$amounts = [];
$colors = [];

// Define category colors
$categoryColors = [
    'Food' => '#FF5733',
    'Transport' => '#33FF57',
    'Entertainment' => '#3357FF',
    'Utilities' => '#F39C12',
    'Health' => '#8E44AD',
    'Other' => '#BDC3C7'
];

while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row['category'];
    $amounts[] = $row['total'];
    $colors[] = $categoryColors[$row['category']] ?? '#000000'; // Default color if category not found
}

mysqli_close($conn);
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
    <h1 class = "WelcomeUser">Welcome User!</h1>

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
