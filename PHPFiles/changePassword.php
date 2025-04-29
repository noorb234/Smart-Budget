<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'User not authenticated']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$currentPassword = $data['currentPassword'] ?? '';
$newPassword = $data['newPassword'] ?? '';

if (empty($currentPassword) || empty($newPassword)) {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
    exit;
}

$username = $_SESSION['username'];

// Get current hashed password from database
$stmt = $pdo->prepare("SELECT password FROM users WHERE username = :username");
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
$hashedPassword = $stmt->fetchColumn();

if (!$hashedPassword || !password_verify($currentPassword, $hashedPassword)) {
    echo json_encode(['success' => false, 'error' => 'Current password is incorrect']);
    exit;
}

// Hash new password and update
$newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$updateStmt = $pdo->prepare("UPDATE users SET password = :password WHERE username = :username");
$updateStmt->bindParam(':password', $newHashedPassword);
$updateStmt->bindParam(':username', $username);

if ($updateStmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update password']);
}
?>
