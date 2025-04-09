<?php
// Set content type for JSON response
header("Content-Type: application/json");

// Replace with your actual OpenAI API key
$apiKey = ""; // 👈 Replace with your key

// Get the raw JSON POST input and decode it
$input = json_decode(file_get_contents("php://input"), true);

// Extract individual form fields safely
$item = $input['item'] ?? '';
$price = $input['price'] ?? '';
$amount = $input['amount'] ?? '';
$time = $input['time'] ?? '';
$frequency = $input['frequency'] ?? '';

// Build the prompt for OpenAI
$prompt = "I want to buy '$item' which costs $price. I can save $amount $frequency over $time. 
Give me budgeting advice and estimate when I can buy it.";

// OpenAI API endpoint and payload
$url = "https://api.openai.com/v1/chat/completions";

$data = [
    "model" => "gpt-3.5-turbo",
    "messages" => [
        ["role" => "system", "content" => "You are a helpful financial advisor providing budgeting advice."],
        ["role" => "user", "content" => $prompt]
    ],
    "temperature" => 0.7
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
