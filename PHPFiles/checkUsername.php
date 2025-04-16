<?php
require_once 'config.php';

// Get the username from the POST request
$data = json_decode(file_get_contents("php://input"));
$username = $data->username;

// Ensure the username is not empty
if (empty($username)) {
    echo json_encode(['success' => false, 'error' => 'Username is required']);
    exit;
}

// Prepare SQL query to check if the username exists
$sql = "SELECT security_question_1, security_question_2 FROM users WHERE username = :username";

try {
    // Prepare the PDO statement
    $stmt = $pdo->prepare($sql);

    // Bind the parameter to the statement
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    // Execute the statement
    $stmt->execute();

    // Check if any row is returned
    if ($stmt->rowCount() > 0) {
        // Fetch the security questions
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return success response with security questions
        echo json_encode([
            'success' => true,
            'securityQuestions' => [
                'firstQuestion' => $user['security_question_1'],
                'secondQuestion' => $user['security_question_2']
            ]
        ]);
    } else {
        // Username not found
        echo json_encode(['success' => false]);
    }
} catch (PDOException $e) {
    // Return error message if any exception occurs
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>