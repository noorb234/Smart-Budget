<?php
session_start();
include 'config.php';

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
}

// Fetch transactions for the user
$query = "SELECT t.transaction_id, t.user_id, t.category_id, t.transaction_amount, 
	t.transaction_date, t.transaction_type, t.note, t.is_recurring, c.category_name
	FROM transaction t
    JOIN category c ON t.category_id = c.category_id
    WHERE t.user_id = :user_id
    ORDER BY t.transaction_date DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Gets categories for dropdown list
$categoryQuery = "SELECT category_id, category_name FROM category";
$categoryStmt = $pdo->prepare($categoryQuery);
$categoryStmt->execute();
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle deleting a transaction
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteTransaction'])) {
    $transaction_id = $_POST['transaction_id'];

    // Prepare statement to fetch the transaction details to adjust the budget
    $fetchTransactionQuery = "SELECT transaction_amount, transaction_type, category_id, transaction_date FROM transaction WHERE transaction_id = :transaction_id";
    $stmt = $pdo->prepare($fetchTransactionQuery);
    $stmt->bindParam(':transaction_id', $transaction_id, PDO::PARAM_INT);
    $stmt->execute();
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($transaction) {
        // Transaction details
        $amount = $transaction['transaction_amount'];
        $type = strtoupper($transaction['transaction_type']);
        $category_id = $transaction['category_id'];
        $transaction_date = $transaction['transaction_date'];

        // Retrieve the user's budget for the same month and category
        $budgetQuery = "SELECT budget_id, current_spending FROM budget 
                        WHERE user_id = :user_id 
                        AND category_id = :category_id 
                        AND budget_month = :transaction_month";
        
        // Get the month and year of the transaction
        $transactionMonth = (new DateTime($transaction_date))->format('Y-m-01');

        // Execute the query
        $stmt = $pdo->prepare($budgetQuery);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':transaction_month', $transactionMonth, PDO::PARAM_STR);
        $stmt->execute();
        $budget = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($budget) {
            $newSpending = $budget['current_spending'];

            // Adjust current spending based on the transaction type
            if ($type == 'EXPENSE') {
                // Subtract the expense from current_spending (reverse expense)
                $newSpending -= $amount;
            } else if ($type == 'INCOME') {
                // Add the income back to current_spending (reverse income)
                $newSpending += $amount;
            }

            // Update the budget
            $updateBudgetQuery = "UPDATE budget 
                                  SET current_spending = :newSpending 
                                  WHERE budget_id = :budget_id";
            $stmt = $pdo->prepare($updateBudgetQuery);
            $stmt->bindParam(':newSpending', $newSpending, PDO::PARAM_STR);  // PARAM_STR is appropriate for DECIMAL values
            $stmt->bindParam(':budget_id', $budget['budget_id'], PDO::PARAM_INT);
            $stmt->execute();
        }

        // Delete the transaction after adjusting the budget
        $deleteQuery = "DELETE FROM transaction WHERE transaction_id = :transaction_id AND user_id = :user_id";
        $stmt = $pdo->prepare($deleteQuery);
        $stmt->bindParam(':transaction_id', $transaction_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Redirect after deletion and budget update
            header('Location: transactions.php');
            exit();
        } else {
            echo "Error: Could not delete the transaction.";
        }
    }
}

// Handle adding a new transaction
 if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addTransaction'])) {
    // Get the form data
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $categoryID = $_POST['categoryID'];
    $note = $_POST['note'];
    $isRecurring = isset($_POST['isRecurring']) ? 1 : 0;

    // Insert the new transaction into the transaction table
    $insertQuery = "INSERT INTO transaction (user_id, category_id, transaction_amount, transaction_date, transaction_type, note, is_recurring) 
                    VALUES (:user_id, :category_id, :transaction_amount, :transaction_date, :transaction_type, :note, :is_recurring)";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':category_id', $categoryID, PDO::PARAM_INT);
    $stmt->bindParam(':transaction_amount', $amount, PDO::PARAM_STR);
    $stmt->bindParam(':transaction_date', $date, PDO::PARAM_STR);  // Assuming date is in 'Y-m-d' format
    $stmt->bindParam(':transaction_type', $type, PDO::PARAM_STR);
    $stmt->bindParam(':note', $note, PDO::PARAM_STR);
    $stmt->bindParam(':is_recurring', $isRecurring, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // After inserting the transaction, update the budget
        $transactionDate = new DateTime($date);
        $year = $transactionDate->format('Y'); 
		$month = $transactionDate->format('m');
		$transactionBudgetDate = new DateTime("$year-$month-01");
		
		$transactionBudgetDateFormatted = $transactionBudgetDate->format('Y-m-d');
        
        // Retrieve the user's budget for the current month and category
        $budgetQuery = "SELECT budget_id, current_spending, monthly_limit 
					FROM budget 
					WHERE user_id = :user_id 
					AND category_id = :category_id 
					AND budget_month = :transactionBudgetDate";
        $stmt = $pdo->prepare($budgetQuery);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $categoryID, PDO::PARAM_INT);
        $stmt->bindParam(':transactionBudgetDate', $transactionBudgetDateFormatted, PDO::PARAM_INT);
        $stmt->execute();
        $budget = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // If the budget for the month exists, update the current_spending
        if ($budget) {
            $newSpending = $budget['current_spending'];
            if ($type == 'EXPENSE') {
                // Add the expense without checking the limit
                $newSpending += $amount;
            } else if ($type == 'INCOME') {
                // Subtract income from the current_spending
                $newSpending -= $amount;
            }

            // Update the budget with the new current_spending value
            $updateBudgetQuery = "UPDATE budget 
                          SET current_spending = :newSpending 
                          WHERE budget_id = :budget_id 
                          AND user_id = :user_id 
                          AND category_id = :category_id";
            $stmt = $pdo->prepare($updateBudgetQuery);
			$stmt->bindParam(':newSpending', $newSpending, PDO::PARAM_STR);  // PARAM_STR is correct for DECIMAL values
			$stmt->bindParam(':budget_id', $budget['budget_id'], PDO::PARAM_INT);
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);  // Bind user_id
			$stmt->bindParam(':category_id', $categoryID, PDO::PARAM_INT); 
            $stmt->execute();
            
        } else {
            // No budget exists, create one with transaction amount as the default monthly_limit
            $monthly_limit = $amount;
            $current_spending = ($type === 'EXPENSE') ? $amount : -$amount;
        
            $insertBudgetQuery = "INSERT INTO budget (user_id, category_id, monthly_limit, current_spending, budget_month) 
                                  VALUES (:user_id, :category_id, :monthly_limit, :current_spending, :budget_month)";
            $stmt = $pdo->prepare($insertBudgetQuery);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $categoryID, PDO::PARAM_INT);
            $stmt->bindParam(':monthly_limit', $monthly_limit, PDO::PARAM_STR);
            $stmt->bindParam(':current_spending', $current_spending, PDO::PARAM_STR);
            $stmt->bindParam(':budget_month', $transactionBudgetDateFormatted, PDO::PARAM_STR);
            $stmt->execute();
        }
        // Optionally, redirect after successful insertion
        header('Location: transactions.php');
        exit();
    } else {
        echo "Error: Could not add the transaction.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Transactions</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <script src="include.js"></script>
	<script src="toggleTheme.js"></script>

</head>
<style>
    .CSVbutton {
        padding-right: 5px;
        text-align: right;
        background: none;
        border: none;
        color: blue;  /* Hyperlink color */
        font-size: 16px;  /* Adjust font size */
        cursor: pointer;
        text-decoration: underline;  /* Makes it look like a hyperlink */
    }

    .CSVbutton:hover {
        color: darkblue;  /* Change color on hover */
        text-decoration: none;  /* Optional: Remove underline on hover */
    }
</style>

<body class="<?php echo ($theme_preference === 'dark mode') ? 'dark-mode' : 'light-mode'; ?>">
    <?php include 'header.php'; ?></div>

    <h1 class = "WelcomeUser">Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>

    <main class = "mainBody">
        <nav class = "sidebar">
            <a class="sideTab" href ="dashboard.php">Profile</a>
            <a class="sideTab" href ="transactions.php">Transactions</a>
            <a class="sideTab" href = "viewReports.php">View Your Reports</a>
            <a class="sideTab" href = "BudgetScreen.php">Set A Budget</a>
            <a class="GoalssideTab" href = "goal.php">BudgetWise AI</a>
        </nav>
        <div class = "transactions">
        <h2>Transaction History</h2>
        <a class= "CSVbutton" onclick="downloadCSV()">Export as CSV</a>
        <table>
            <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Category</th>
                <th>Note</th>
                <th>Recurring</th>
            </tr>
            <?php foreach ($transactions as $transaction): ?>
                <?php
                    $isExpense = strtoupper($transaction['transaction_type']) === 'EXPENSE';
                    $rowStyle = $isExpense ? 'style="color: red;"' : '';
                ?>
                <tr <?php echo $rowStyle; ?>>
                    <td><?php echo htmlspecialchars($transaction['transaction_date']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['transaction_amount']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['transaction_type']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['category_name']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['note']); ?></td>
                    <td><?php echo $transaction['is_recurring'] ? 'Yes' : 'No'; ?></td>
					<td>
						<form method="POST" style="display:inline;">
							<input type="hidden" name="transaction_id" value="<?php echo $transaction['transaction_id']; ?>">
							<button type="submit" name="deleteTransaction" onclick="return confirm('Are you sure you want to delete this transaction?');">Delete</button>
						</form>
					</td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h3>Add Transaction</h3>
        <form method="POST">
            <label for="date">Date:</label>
            <input type="date" name="date" required>

            <label for="amount" >Amount:</label>
            <input type="number" name="amount" step="0.01" required placeholder="e.g $100">

            <label for="type">Type:</label>
            <select name="type" required>
				<option value="" disabled selected>Select Type of Transaction</option>
                <option value="INCOME">Income</option>
                <option value="EXPENSE">Expense</option>
            </select>

            <label for="categoryID">Category:</label>
            <select name="categoryID" required>
                <option value="" disabled selected>Select a Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="note">Note:</label>
            <input type="text" name="note" placeholder="e.g Rent">

            <label for="isRecurring">Recurring:</label>
            <input type="checkbox" name="isRecurring">

            <button type="submit" name="addTransaction">Add</button>
            
    
        </form>
        </div>
</main>

</body>
<footer class = "footer">
    <div id = "footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
    
</footer>

<script>

function downloadCSV() {
    const rows = [['Date', 'Amount', 'Type', 'Category', 'Note', 'Recurring']];
    document.querySelectorAll('table tr:not(:first-child)').forEach(row => {
        const cols = Array.from(row.querySelectorAll('td')).map(td => td.innerText.trim());
        rows.push(cols);
    });

    const csvContent = "data:text/csv;charset=utf-8," 
        + rows.map(e => e.join(",")).join("\n");

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "SmartBudget_Transactions.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

</html>
