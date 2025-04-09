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

// Handle adding a new transaction
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addTransaction'])) {
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $type = $_POST['type'];
    $categoryID = $_POST['categoryID'];
    $note = $_POST['note'];
    $isRecurring = isset($_POST['isRecurring']) ? 1 : 0;

    $insertQuery = "INSERT INTO transaction (user_id, category_id, transaction_amount, transaction_date, transaction_type, note, is_recurring) VALUES (:userID, :categoryID, :amount, :date, :type, :note, :isRecurring)";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->bindParam(':userID', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':categoryID', $categoryID, PDO::PARAM_INT);
    $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);  // Assuming date is in 'Y-m-d' format
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->bindParam(':note', $note, PDO::PARAM_STR);
    $stmt->bindParam(':isRecurring', $isRecurring, PDO::PARAM_INT);  // BIT type as 0 or 1
    
    if ($stmt->execute()) {
        header('Location: transactions.php');
        exit();
    } else {
        $error = "Error adding transaction.";
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
                <option value="Income">Income</option>
                <option value="Expense">Expense</option>
            </select>

            <label for="categoryID">Category:</label>
            <input type="text" name="categoryID" required>

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
