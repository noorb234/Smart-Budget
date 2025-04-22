<?php
// Set content type for JSON response
header("Content-Type: application/json");

// Replace with your actual OpenAI API key
$apiKey = "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"; // 👈 Replace with your key

// Get the raw JSON POST input and decode it
$input = json_decode(file_get_contents("php://input"), true);

// Extract individual form fields safely
$item = $input['item'] ?? '';
$price = $input['price'] ?? '';
$amount = $input['amount'] ?? '';
$time = $input['time'] ?? '';
$frequency = $input['frequency'] ?? '';

// Build the prompt for OpenAI
$prompt = "
You are a financial advisor helping a user make a smart plan for a purchase.

Here is the information:
- Item: \"$item\"
- Item Price: \$$price
- Saving Amount: \$$amount per $frequency
- Timeframe Goal: $time

Additionally, provide the following:
1. Realistic estimate of when the user can afford the item based on their savings.
2. Suggest 2–3 personalized budgeting tips to help them reach their goal faster.
3. Mention any financial red flags if applicable.

Speak clearly and concisely, like a friendly human advisor. 
If the savings plan won't realistically meet the goal, explain why and suggest alternatives.
";

// OpenAI API endpoint and payload
$url = "https://api.openai.com/v1/chat/completions";

$data = [
    "model" => "gpt-3.5-turbo",
    "messages" => [
        ["role" => "system", "content" => "You are a helpful financial advisor providing budgeting advice."],
        ["role" => "user", "content" => $prompt]
    ],
    "temperature" => 0.4,
    "max_tokens" => 1000
];

$headers = [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
];

// Initialize cURL and send the request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if ($response === false) {
    echo json_encode(["reply" => "Error: " . curl_error($ch)]);
} else {
    $decoded = json_decode($response, true);
    $reply = $decoded['choices'][0]['message']['content'] ?? 'No advice found.';
    echo json_encode(["reply" => $reply]);
}

curl_close($ch);
?>
