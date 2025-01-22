<?php
header('Content-Type: application/json');
define('ACCESS_ALLOWED', true);

session_start();
require_once '../mysql.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'message' => 'Invalid CSRF token.']);
        exit;
    }

    $userId = intval($_POST['id']);
    $usernameToDelete = $_POST['username'] ?? '';

    if (empty($userId) || empty($usernameToDelete)) {
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit;
    }

    $result = $db->query(
        "DELETE FROM users WHERE id = ? AND username = ?", 
        [$userId, $usernameToDelete]
    );

    if ($result > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
    }

    exit;
}
?>
