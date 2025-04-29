<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$theme = $data['theme'] ?? 'light mode';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);

    $stmt = $pdo->prepare("UPDATE users SET preferences = :theme WHERE username = :username");
    $stmt->execute([
        ':theme' => $theme,
        ':username' => $_SESSION['username']
    ]);

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}