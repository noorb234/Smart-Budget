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

	$query = "SELECT category_id, category_name FROM category";
	$stmt = $pdo->prepare($query);
	$stmt->execute();

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

//$planned_purchase = 3500;
//$worst_case_spending = 4005;
//$best_case_spending = 3250;
//$budget_status = ($worst_case_spending > $total_budget) ? 'over' : 'within';

$months = [];
$budgets = [];
$spending_plus_purchase = [];
$messages = [];
$tooltip_data = [];
$planned_purchase = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

for ($i = 0; $i < 3; $i++) {
    $month_label = date('F', strtotime("+$i months"));
    $budget_month = date('Y-m-01', strtotime("+$i months"));

    $query = "SELECT SUM(monthly_limit) AS monthly_budget FROM budget WHERE user_id = :user_id AND budget_month = :budget_month";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':budget_month', $budget_month, PDO::PARAM_STR);
    $stmt->execute();
    $monthly_budget = $stmt->fetchColumn();
    $stmt->closeCursor();

    $anticipated_spending = $worst_case_spending + $planned_purchase;

    $months[] = $month_label;
    $budgets[] = $monthly_budget ?: 0;
    $spending_plus_purchase[] = $anticipated_spending;

    $difference = ($monthly_budget ?: 0) - $anticipated_spending;

    if ($difference >= 0) {
        $messages[] = "<span style='color: green;'>$month_label: ✅ You can afford the purchase. You'll have $" . number_format($difference, 2) . " left.</span>";
        $tooltip_data[] = "Under budget by $" . number_format($difference, 2);
    } else {
        $monthly_saving = ceil(abs($difference) / max(1, 3 - $i)); 
        $messages[] = "<span style='color: red;'>$month_label: ❌ Over budget. You'd need to save $" . number_format(abs($difference), 2) . " (about $" . number_format($monthly_saving, 2) . "/mo).</span>";
        $tooltip_data[] = "Over budget by $" . number_format(abs($difference), 2);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Future Planning</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta charset="UTF-8">
    <script src="include.js"></script>
</head>

<body onload="includeHeader()">
    <div include-header = "header.php"></div>
    <main class="futureBody">
        <h1 class="WhatIfHeading">Welcome to the What If? Calculator</h1>
        <h2 class="subheading">If you're trying to make a big purchase, see how it fits into your budget</h2>    
        <!--<h2 class="subheading" style ="text-decoration: underline; font-style: italic; color:black">Input these values:</h2>-->
        <div class = "calculatorContainer">
        <form class="inputForm">
            <div class="form-group">
                <label for="category">Payment Type:</label>
				<select required id="category" name="category">
					<option value="" disabled selected>Select Category</option>
					<?php
					while ($row = $stmt->fetch()) {
						echo "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
					}
					?>
                <!-- input required type="text" id="category" name="category" placeholder="Category" -->
            </div>
    
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input required type="text" id="amount" name="amount" placeholder="$0.00">
            </div>
    
            <div class="form-group">
                <label for="startDate">Anticipated Start Date:</label>
                <input required type="date" id="startDate" name="date" placeholder="MM/DD/YY">
            </div>
    
            <div class="form-group">
                <label for="duration">Payment Duration:</label>
                <input required type="text" id="duration" name="duration" placeholder="None">
            </div>
    
            <div class="form-group">
                <label for="frequency">Payment Frequency:</label>
                <input required type="text" id="frequency" name="frequency" placeholder="None">
            </div>
    
            <div class="checkbox-group">
                <label for="sameBudget">Keep in the same budget?</label> 
                <input type="checkbox" id="sameBudget">
            </div>
    
            <button type="submit" class="submitButton">Calculate</button>
        </form>
        <div class="calculator">
            <!---<h2>Output</h2>
            <p><b>Planned Purchase:</b> Car ($<?php echo number_format($planned_purchase, 2); ?>)</p>
            <p><b>Best Case Scenario:</b> October, you spent $<?php echo number_format($best_case_spending, 2); ?>. <span style="color: green;">This fits into your budget</span></p>
            <p><b>Worst Case Scenario:</b> You spent $<?php echo number_format($worst_case_spending, 2); ?>. <span style="color: <?php echo ($budget_status == 'over') ? 'red' : 'green'; ?>;">This <?php echo ($budget_status == 'over') ? 'puts you over budget' : 'fits into your budget'; ?></span></p>
            --->
            <h3>Smart Budget Recommendation:</h3>
                <ul>
                    <?php foreach ($messages as $msg): ?>
                        <li><?php echo $msg; ?></li>
                    <?php endforeach; ?>
                </ul>

            <h3>Monthly Budget vs Anticipated Spending:</h3>
            <div id="chart-container">
                <canvas id="budgetChart"></canvas>
            </div>
        </div>
    </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
    const ctx = document.getElementById('budgetChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [
                {
                    label: 'Monthly Budget',
                    data: <?php echo json_encode($budgets); ?>,
                    backgroundColor: '#0A599D'
                },
                {
                    label: 'Anticipated Spending',
                    data: <?php echo json_encode($spending_plus_purchase); ?>,
                    backgroundColor: '#D9534F'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Amount ($)'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed.y;
                            const label = context.dataset.label;
                            const index = context.dataIndex;
                            const extra = <?php echo json_encode($tooltip_data); ?>;

                            if (label === "Anticipated Spending") {
                                return `${label}: $${value.toLocaleString()} (${extra[index]})`;
                            } else {
                                return `${label}: $${value.toLocaleString()}`;
                            }
                        }
                    }
                }
            }
        }
    });
</script>


    

</body>
<footer class = "footer">
    <div id = "footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
    
</footer>

</html>