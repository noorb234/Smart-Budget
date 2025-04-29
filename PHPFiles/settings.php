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
	<script src="toggleTheme.js"></script>
</head>
<body class="<?php echo ($theme_preference === 'dark mode') ? 'dark-mode' : 'light-mode'; ?>">
    <?php include 'header.php'; ?>

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
      <input type="password" id="currentPassword" class="input">
      <label>New Password</label>
      <input type="password" id="newPassword" class="input">
      <label>Confirm Password</label>
      <input type="password" id="confirmPassword" class="input">

      <span id="passwordMismatchWarning" style="color: red; display: none;">New passwords do not match.</span>
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

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const newPassword = document.getElementById("newPassword");
    const confirmPassword = document.getElementById("confirmPassword");
    const warning = document.getElementById("passwordMismatchWarning");
    const saveBtn = document.querySelector(".save-btn");

    function checkPasswordsMatch() {
      if (confirmPassword.value === "") {
        warning.style.display = "none";
        confirmPassword.style.border = "";
        saveBtn.disabled = false;
      } else if (newPassword.value === confirmPassword.value) {
        warning.style.display = "none";
        confirmPassword.style.border = "2px solid green";
        saveBtn.disabled = false;
      } else {
        warning.style.display = "inline";
        confirmPassword.style.border = "2px solid red";
        saveBtn.disabled = true;
      }
    }

    newPassword.addEventListener("input", checkPasswordsMatch);
    confirmPassword.addEventListener("input", checkPasswordsMatch);
  });

  document.addEventListener("DOMContentLoaded", () => {
  const saveBtn = document.querySelector(".save-btn");

  saveBtn.addEventListener("click", () => {
    const currentPassword = document.getElementById("currentPassword").value;
    const newPassword = document.getElementById("newPassword").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    if (!currentPassword || !newPassword || !confirmPassword) {
      alert("Please fill in all fields.");
      return;
    }

    if (newPassword !== confirmPassword) {
      alert("New passwords do not match.");
      return;
    }

    fetch("changePassword.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ currentPassword, newPassword })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert("Password changed successfully!");
        window.location.reload(); // or redirect as needed
      } else {
        alert(data.error || "Failed to change password.");
      }
    })
    .catch(error => {
      console.error("Error:", error);
      alert("An unexpected error occurred.");
    });
  });
});

</script>

</body>
<footer class = "footer">
    <div id = "footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
    
</footer>

</html>