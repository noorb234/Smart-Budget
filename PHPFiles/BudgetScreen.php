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

    // Return the budget as a JSON response
    echo json_encode(['budget' => $category_budget]);
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
    <h1 class = "WelcomeUser">Welcome <?php echo htmlspecialchars($un); ?>!</h1>

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

				<label id="category-budget-label"><b>Budget for Selected Category: </b></label>
				<span id="category-budget">$0.00</span><br>

                <button class="button1" type="button" onclick="editBudget()">Edit Budget</button>
                <button class="button2" type="submit">Save Budget</button>
            </form>
        </div>
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
</script>
	
</body>

<footer class = "footer">
    <div id = "footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
</footer>

</html>