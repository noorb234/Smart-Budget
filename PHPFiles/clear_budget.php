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

        // If user is not found
        if (!$userId) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            exit;
        }

        // Get the current date and set the budget_month to the first day of this month (e.g., '2025-04-01')
        $currentMonth = date('Y-m-01');  // This will be '2025-04-01' for April 2025

        // Prepare statement to retrieve the budget for the selected category
        $budgetQuery = "SELECT budget_id, monthly_limit FROM budget 
                        WHERE user_id = :user_id 
                        AND category_id = :category_id 
                        AND DATE_FORMAT(budget_month, '%Y-%m-%d') = :current_month"; // Use full date format here
        $stmt = $pdo->prepare($budgetQuery);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':current_month', $currentMonth, PDO::PARAM_STR);
        $stmt->execute();
        $budget = $stmt->fetch(PDO::FETCH_ASSOC);

        // If a budget exists for this category and month, reset the monthly_limit to 0
        if ($budget) {
            // Update the monthly_limit to 0 (clear the budget)
            $updateBudgetQuery = "UPDATE budget SET monthly_limit = 0.00 WHERE budget_id = :budget_id";
            $stmt = $pdo->prepare($updateBudgetQuery);
            $stmt->bindParam(':budget_id', $budget['budget_id'], PDO::PARAM_INT);
            $stmt->execute();

            // Fetch the updated budget to return as part of the response
            $stmt = $pdo->prepare("SELECT monthly_limit FROM budget WHERE budget_id = :budget_id");
            $stmt->bindParam(':budget_id', $budget['budget_id'], PDO::PARAM_INT);
            $stmt->execute();
            $updatedBudget = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return success response with the updated budget
            echo json_encode([
                'status' => 'success',
                'message' => 'Budget cleared successfully.',
                'monthly_limit' => $updatedBudget['monthly_limit']  // Return the updated monthly_limit
            ]);
        } else {
            // No budget found for this category in the current month
            echo json_encode(['status' => 'error', 'message' => 'No budget found for this category in the current month.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>