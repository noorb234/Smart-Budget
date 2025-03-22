<?php

session_start();

$un = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
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
    <main class = settings>
        <label class="welcome-user-label"><b>Welcome, <?php echo htmlspecialchars($un); ?>!</b></label><br>
        <label class="NameScreen-Label"> <b>Settings </b> </label>
        <label class="Notis-Label"><b>Notifications Preferences:  </b></label>

        <form> 
            <input type="checkbox" id="Balancecheckbox">
            <label class="BalanceLabel">Balance</label> 
            <input type="checkbox" id="BudgePlancheckbox">
            <label class="BudgePlanLabel">Budget Plan</label> 
            <input type="checkbox" id="Transactionscheckbox">
            <label class="TransactionsLabel">Transactions</label> 
        </form>

        <form > 
            <input type="checkbox" id="Appareancecheckbox">
            <label class="AppareanceLabel"><b>Appareance</b></label> 
        </form>

        <form>
            <Label class="currentPasswordLabel"><b>Enter Current Password </b></Label>
            <input class="currentPasswordText" type="password"></input>
            <Label class="changePasswordLabel"><b>Enter New Password </b></Label>
            <input class="changePasswordText" type="password"></input>
            <Label class="confirmPasswordLabel"><b>Confirm New Password </b></Label>
            <input class="confirmPasswordText" type="password"></input>

        </form>
    
        <button class="ResetDefaultButton" onclick=" "> Reset to Default </button>
        <button class="saveButton" onclick=" "> Save </button>
        <label class="logOut-label" onclick=" "><b>Log Out</b></label>
        <label class="Delete-account-label" onclick=" "><b>Delete Account</b></label>
</main>
</body>
<footer class = "footer">
    <div id = "footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
    
</footer>
</html>