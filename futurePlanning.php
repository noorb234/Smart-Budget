<!DOCTYPE html>
<html>
<head>
    <title>Future Planning</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta charset="UTF-8">
    <script src="include.js"></script>
</head>

<body onload="includeHeader()">
    <div include-header = "header.php"></div>
    <main class="futureBody">
        <h1 class="WhatIfHeading">Welcome to the What If? Calculator</h1>
        <h2 class="subheading">If you're trying to make a big purchase, see how it fits into your budget</h2>    
        <h2 class="subheading" style ="text-decoration: underline; font-style: italic; color:black">Input these values:</h2>
    
        <form class="inputForm">
            <div class="form-group">
                <label for="category">Payment Type:</label>
                <input required type="text" id="category" name="category" placeholder="Category">
            </div>
    
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input required type="text" id="amount" name="amount" placeholder="$0.00">
            </div>
    
            <div class="form-group">
                <label for="startDate">Anticipated Start Date:</label>
                <input required type="date" id="startDate" name="date" placeholder="MM/DD/YY">
            </div>
    
            <div class="form-group">
                <label for="duration">Payment Duration:</label>
                <input required type="text" id="duration" name="duration" placeholder="None">
            </div>
    
            <div class="form-group">
                <label for="frequency">Payment Frequency:</label>
                <input required type="text" id="frequency" name="frequency" placeholder="None">
            </div>
    
            <div class="checkbox-group">
                <label for="sameBudget">Keep in the same budget?</label> 
                <input type="checkbox" id="sameBudget">
            </div>
    
            <button type="submit" class="submitButton">Calculate</button>
        </form>
    </main>
    

</body>
<footer class = "footer">
    <div id = "footerSection">
        <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
    </div>
    
</footer>

</html>