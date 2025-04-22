<?php
require_once 'config.php';
session_start();

if (isset($_SESSION['username'])) {
    try {
        $username = $_SESSION['username'];

        // Get user ID
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $userId = $stmt->fetchColumn();

        if (!$userId) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            exit;
        }

        // Get current month
        $currentMonth = date('Y-m-01');

        // Query all budgets for the user this month
        $stmt = $pdo->prepare("
            SELECT b.monthly_limit, b.current_spending, c.category_name
            FROM budget b
            JOIN category c ON b.category_id = c.category_id
            WHERE b.user_id = :user_id AND DATE_FORMAT(b.budget_month, '%Y-%m-%d') = :current_month
        ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':current_month', $currentMonth);
        $stmt->execute();
        $budgets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($budgets) {
			$totalBudget = 0;
			$processedBudgets = [];

			foreach ($budgets as $b) {
				$totalBudget += (float)$b['monthly_limit']; // Ensure this is a float

				$processedBudgets[] = [
					'category_name' => $b['category_name'],
					'monthly_limit' => (float)$b['monthly_limit'] // Cast to float for JS compatibility
				];
			}

			echo json_encode([
				'status' => 'success',
				'budgets' => $processedBudgets,
				'total_budget' => $totalBudget
			]);
		} else {
			echo json_encode([
				'status' => 'success',
				'budgets' => [],
				'total_budget' => 0
			]);
		}

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>