<?php
require_once 'config.php';

// Get the data from the POST request
$data = json_decode(file_get_contents("php://input"));
$username = $data->username;
$firstAnswer = $data->firstAnswer;
$secondAnswer = $data->secondAnswer;
$newPassword = $data->newPassword;

// Ensure all fields are provided
if (empty($username) || empty($firstAnswer) || empty($secondAnswer) || empty($newPassword)) {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
    exit;
}

// Prepare SQL query to get the security answers for the username
$sql = "SELECT security_answer_1, security_answer_2, password FROM users WHERE username = :username";

try {
    // Prepare the PDO statement
    $stmt = $pdo->prepare($sql);

    // Bind the parameter to the statement
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    // Execute the statement
    $stmt->execute();

    // Check if any row is returned
    if ($stmt->rowCount() > 0) {
        // Fetch the user's details
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
		
		//Decodes security questions so special characters show on user side
		$decodedQuestion1 = htmlspecialchars_decode($user['security_question_1']);
        $decodedQuestion2 = htmlspecialchars_decode($user['security_question_2']);

        // Validate the security answers
        if ($user['security_answer_1'] === $firstAnswer && $user['security_answer_2'] === $secondAnswer) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Prepare SQL query to update the password
            $updateSql = "UPDATE users SET password = :password WHERE username = :username";

            // Prepare the update statement
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $updateStmt->bindParam(':username', $username, PDO::PARAM_STR);

            // Execute the update statement
            if ($updateStmt->execute()) {
                echo json_encode(['success' => true,
									'securityQuestions' => [
									'firstQuestion' => $decodedQuestion1,
									'secondQuestion' => $decodedQuestion2]
									]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update password']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Incorrect security answers']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Username not found']);
    }
} catch (PDOException $e) {
    // Return error message if any exception occurs
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>