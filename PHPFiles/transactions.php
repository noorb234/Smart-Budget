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
            // If no budget exists for the month, notify user to go to the budget screen
            $error = "No budget exists for this category and month. Please go to the Budget Screen to set a budget, then return here to add the transaction.";
            echo "<script>alert('$error'); window.location.href='BudgetScreen.php';</script>";
            exit();  // Stop further execution
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
        <div class = "transactions">
        <h2>Transaction History</h2>
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
				<tr>
					<td><?php echo htmlspecialchars($transaction['transaction_date']); ?></td>
					<td><?php echo htmlspecialchars($transaction['transaction_amount']); ?></td>
					<td><?php echo htmlspecialchars($transaction['transaction_type']); ?></td>
					<td><?php echo htmlspecialchars($transaction['category_name']); ?></td> <!-- Displaying category_name -->
					<td><?php echo htmlspecialchars($transaction['note']); ?></td>
					<td><?php echo $transaction['is_recurring'] ? 'Yes' : 'No'; ?></td>
				</tr>
			<?php endforeach; ?>
        </table>

        <h3>Add Transaction</h3>
        <form method="POST">
            <label for="date">Date:</label>
            <input type="date" name="date" required>

            <label for="amount">Amount:</label>
            <input type="number" name="amount" step="0.01" required>

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
            <input type="text" name="note">

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

</html>
