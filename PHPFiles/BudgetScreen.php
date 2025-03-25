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
        
        <div class = "setABudget">

            <label class="your-budget"><b>Your Budget for the Month: $10</b></label><br>
            
            <form>
			  <label class="Category-label"><b>Category: </b></label>
				<select required id="category" name="category">
					<option value="" disabled selected>Select Category</option>
					<?php
					while ($row = $stmt->fetch()) {
						echo "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
					}
					?><br> 
               <button class="button1" type="button" onclick="editBudget()">Edit Budget</button>
               <button class="button2" type="submit">Save Budget</button> 
            </form>
        </div>
    </div>
    </main>
    
</body>

<footer class = "footer">
    <div id = "footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
</footer>
</html> 