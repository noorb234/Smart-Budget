<?php
include 'config.php';

session_start();

$un = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';

if (isset($_SESSION['username'])) {
	$username = $_SESSION['username'];
	$query_first_name = "SELECT firstName FROM users WHERE username = :username";
	$stmt_first_name = $pdo->prepare($query_first_name);
    $stmt_first_name->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt_first_name->execute();

    // Fetches the first name
    $first_name = $stmt_first_name->fetchColumn();
    $stmt_first_name->closeCursor();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="styles.css">
<link rel="stylesheet" type="text/css" href="styles2.css"> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="include.js"></script>
</head>
<body onload="includeHeader()">
    <div include-header = "header.php"></div>
    <main class="settings-container">
  <h2 class="page-title">Settings</h2>

  <section class="settings-section">
    <h3>Notification Preferences</h3>
    <div class="checkbox-group">
      <label><input type="checkbox" id="Balancecheckbox"> Balance</label>
      <label><input type="checkbox" id="BudgePlancheckbox"> Budget Plan</label>
      <label><input type="checkbox" id="Transactionscheckbox"> Transactions</label>
    </div>
  </section>

  <section class="settings-section">
    <h3>Appearance</h3>
    <label><input type="checkbox" id="Appareancecheckbox"> Enable Dark Mode</label>
  </section>

  <section class="settings-section">
    <h3>Change Password</h3>
    <div class="password-group">
      <label>Current Password</label>
      <input type="password" class="input">
      <label>New Password</label>
      <input type="password" class="input">
      <label>Confirm Password</label>
      <input type="password" class="input">
    </div>
  </section>

  <div class="settings-actions">
    <button class="btn reset-btn">Reset to Default</button>
    <button class="btn save-btn">Save Changes</button>
  </div>

  <div class="danger-zone">
    <span class="logout">Log Out</span>
    <span class="delete-account">Delete Account</span>
  </div>
</main>

</body>
<footer class = "footer">
    <div id = "footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
    
</footer>
</html>