<!-- <?php
// Your OpenAI API Key (replace with your actual API key)

$apiKey = "";
// OpenAI API URL (use the appropriate endpoint you want to call)
$url = "https://api.openai.com/v1/completions";  // You might use different endpoints like chat/completions

// Data for the API request (example for completions endpoint)
$data = array(
    'model' => 'gpt-3.5 turbo',  // You can change this to any other model you need
    'prompt' => 'Say something creative!',
    'max_tokens' => 200,
);

// Initialize cURL session
$ch = curl_init($url);

// Set up the HTTP headers
$headers = array(
    "Authorization: Bearer " . $apiKey,
    "Content-Type: application/json"
);

// Convert the data array to JSON
$data_json = json_encode($data);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return the response as a string
curl_setopt($ch, CURLOPT_POST, true);            // HTTP method POST
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);  // Add data as POST body
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  // Set the headers

// Execute the cURL request
$response = curl_exec($ch);

// Check for errors
if ($response === false) {
    echo "cURL Error: " . curl_error($ch);
} else {
    // Decode and display the response
    $response_data = json_decode($response, true);
    print_r($response_data);
}

// Close the cURL session
curl_close($ch);
?> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles2.css"> 
    <link rel="stylesheet" type="text/css" href="styles3.css">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <meta charset="UF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goals</title>
    <script src="include.js"></script>

</head>
<body onload="includeHeader()">
    <!-- <div include-header="header.php"></div>  -->
    <main class="goals">
        
    <div class="goalsContrainer">
     
    <form>
    <input type="text" id="timeFrameTextField">
    <input type="text" id="thingToBuy">
    <label class="randomLabel">Balance</label> 

    </form>    
    </div>


    </main>
 
 

    <!-- <footer class="footer">
        <div id="footerSection">
            <p>Smart Budget<br>New York, NY<br>123-456-7890<br>© 2025 SmartBudget</p>
        </div>
    </footer> -->

</body>
</html>
