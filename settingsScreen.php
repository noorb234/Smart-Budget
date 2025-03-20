<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles2.css"> 
    <script src="include.js"></script>
</head>
<body onload="includeHeader()">
    <div include-header = "header.php"></div>
    
    <label class="welcome-user-label"><b>Welcome:  "user"</b></label><br>
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

    <div class="dropdown">
        <button class="dropdown-button">Menu</button>
        <div class="dropdown-content">
            <a href="settingScreen.php">Option 1</a>
            <a href="">Appareance</a>  <!-- This should call the same function when the appearance box is checked --> 
            <a href="#">Log out</a>  <!-- This should call the same function when the Log out label is pressed --> 
            <a href="#">what else?</a>
        </div>
    </div>

</body>
</html>