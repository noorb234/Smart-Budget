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
	
	// Get the first day of the previous month
	$prev_month = date('Y-m-01', strtotime("first day of last month")); 

	// Prepare statement to get the previous month's budget for the selected category
	$query_prev_month = "SELECT monthly_limit FROM budget WHERE category_id = :category_id AND user_id = :user_id AND budget_month = :prev_month";
	$stmt_prev_month = $pdo->prepare($query_prev_month);

	// Bind the parameters
	$stmt_prev_month->bindParam(':category_id', $category_id, PDO::PARAM_INT);
	$stmt_prev_month->bindParam(':user_id', $user_id, PDO::PARAM_INT);
	$stmt_prev_month->bindParam(':prev_month', $prev_month, PDO::PARAM_STR);

	// Execute the query
	$stmt_prev_month->execute();

	// Fetch the previous month's budget
	$prev_month_budget = $stmt_prev_month->fetchColumn();

	// If no budget found, set it to 0.00
	$prev_month_budget = $prev_month_budget ? number_format($prev_month_budget, 2) : '0.00';
	$stmt_prev_month->closeCursor();
}

if (isset($_GET['category'])) {
    $category_id = $_GET['category'];

    // Prepare statement to get the budget for the selected category
    $query = "SELECT monthly_limit FROM budget WHERE category_id = :category_id AND user_id = :user_id AND budget_month = :current_month";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':current_month', $current_month, PDO::PARAM_STR);
    $stmt->execute();

    // Fetch the budget for the selected category
    $category_budget = $stmt->fetchColumn();
    
    // If there's no budget set, return 0.00
    $category_budget = $category_budget ? number_format($category_budget, 2) : '0.00';

    // Get the first day of the previous month
    $prev_month = date('Y-m-01', strtotime("first day of last month")); // Get the first day of last month

    // Prepare statement to get the previous month's budget for the selected category
    $query_prev_month = "SELECT monthly_limit FROM budget WHERE category_id = :category_id AND user_id = :user_id AND budget_month = :prev_month";
    $stmt_prev_month = $pdo->prepare($query_prev_month);
    $stmt_prev_month->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $stmt_prev_month->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_prev_month->bindParam(':prev_month', $prev_month, PDO::PARAM_STR);
    $stmt_prev_month->execute();

    // Fetch the previous month's budget
    $prev_month_budget = $stmt_prev_month->fetchColumn();
    // If no budget found, set it to 0.00
    $prev_month_budget = $prev_month_budget ? number_format($prev_month_budget, 2) : '0.00';
	// Close the previous month statement
    $stmt_prev_month->closeCursor();

    // Return the budget as a JSON response
    echo json_encode([
        'category_budget' => $category_budget,
        'prev_month_budget' => $prev_month_budget
    ]);    
    exit;
}
	
	$query = "SELECT category_id, category_name FROM category";
	$stmt = $pdo->prepare($query);
	$stmt->execute();
	
	$selected_category_id = isset($_GET['category']) ? $_GET['category'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Set a Budget</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="include.js"></script>
	<script src="fetchCategoryBudget.js"></script>
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
        
        <div class = "setABudget">

            <label class="your-budget"><b>Your Budget for the Month: <?php echo isset($total_budget) ? '$' . number_format($total_budget, 2) : '$0.00'; ?></b></label><br>
            
             <form id="budgetForm" method="get">
                <label class="Category-label"><b>Category: </b></label>
                <select required id="category" name="category" onchange="submitForm()">
				<option value="" disabled selected>Select Category</option>
					<?php
					while ($row = $stmt->fetch()) {
						echo "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
					}
					?>
				</select><br><br>

				<div class="budget-container">
					<label id="category-budget-label"><b>Budget for Selected Category: </b></label>
					<span id="category-budget">$0.00</span>
    
					<input type="number" id="edit-budget-input" placeholder="Enter new budget" style="display: none;">
				</div>

				<div class="budget-info">
					<!-- Last Month's Budget is hidden by default, will show only in edit mode -->
					<label id="prev-month-budget-label" style="display: none;"><b>Last Month's Budget: </b></label>
					<span id="prev-month-budget" style="display: none;">
						$<?php echo $prev_month_budget; ?>
					</span>
				</div>

				<button class="button1" type="button" onclick="editBudget()">Edit Budget</button>
				<button class="button2" type="submit" style="display: none;" id="save-budget-btn">Save Budget</button>
            </form>
        </div>
	</main>
    
	<script>
	// Function to automatically submit the form when a category is selected
	function submitForm() {
		const categorySelect = document.getElementById('category');
		const categoryId = categorySelect.value;

		// If a category is selected, fetch its budget using AJAX
		if (categoryId) {
			fetchCategoryBudget(categoryId);
		}
	}

	// Toggle Edit Mode
    function editBudget() {
		const budgetSpan = document.getElementById('category-budget');
		const editBudgetInput = document.getElementById('edit-budget-input');
		const saveBtn = document.getElementById('save-budget-btn');
		const prevMonthBudgetLabel = document.getElementById('prev-month-budget-label');
		const prevMonthBudget = document.getElementById('prev-month-budget');

		// Switch to edit mode
		if (editBudgetInput.style.display === 'none') {
			// Show the input and save button, hide the span
			editBudgetInput.style.display = 'inline';
			saveBtn.style.display = 'inline';
			budgetSpan.style.display = 'none';

			// Show last month's budget label and value
			prevMonthBudgetLabel.style.display = 'inline';
			prevMonthBudget.style.display = 'inline';
		} else {
			// Revert to view mode
			editBudgetInput.style.display = 'none';
			saveBtn.style.display = 'none';
			budgetSpan.style.display = 'inline';

			// Hide last month's budget label and value
			prevMonthBudgetLabel.style.display = 'none';
			prevMonthBudget.style.display = 'none';
		}
	}
	</script>
	
</body>

<footer class = "footer">
    <div id = "footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
</footer>

</html>