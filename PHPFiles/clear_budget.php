<?php
require_once 'config.php';
session_start();

if (isset($_POST['category']) && isset($_SESSION['username'])) {
    try {
        // Get the category ID and user ID from the session
        $categoryId = $_POST['category'];
        $username = $_SESSION['username'];

        // Prepare statement to get user_id for the username
        $query = "SELECT user_id FROM users WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $userId = $stmt->fetchColumn();
        
        // Prepare statement to clear the budget for the current month
        $currentMonth = date('Y-m'); // Current month (e.g., '2025-04')
        $sql = "UPDATE budget SET monthly_limit = 0.00 WHERE user_id = :user_id AND category_id = :category_id AND budget_month = :current_month";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':current_month', $currentMonth, PDO::PARAM_STR);
        
        // Execute the update
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>