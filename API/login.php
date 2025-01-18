<?php
define('ACCESS_ALLOWED', true);

require_once '../mysql.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$apiKey = $_POST['api_key'] ?? null;
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;
$ip = $_SERVER['REMOTE_ADDR'];

$config = include('../config.php');
$apiKeyBase64 = base64_encode($config['api']['key']);

if ($apiKey !== $apiKeyBase64) {
    echo json_encode(['success' => false, 'message' => 'Invalid API Key']);
    exit;
}

$db = new Database();

$blockQuery = $db->query("SELECT attempts, last_attempt FROM login_attempts WHERE ip = ? LIMIT 1", [$ip]);
if ($blockQuery) {
    $result = $blockQuery->fetch_assoc();
        if ($result) {
            $attempts = (int) $result['attempts'];
            $lastAttempt = strtotime($result['last_attempt']);
            $timeDiff = time() - $lastAttempt;

            if ($attempts >= 5 && $timeDiff < 3600) {
                echo json_encode(['success' => false, 'message' => 'Too many login attempts. Try again later.']);
                exit;
            }

            if ($timeDiff >= 3600) {
                $db->query("UPDATE login_attempts SET attempts = 0 WHERE ip = ?", [$ip]);
            }
            }
}

$userQuery = $db->query("SELECT password FROM users WHERE username = ? LIMIT 1", [$username]);

if ($userQuery) {
    $user = $userQuery->fetch_assoc(); 
    if (!$user || !password_verify($password, $user['password'])) {
        $db->query("INSERT INTO login_attempts (ip, attempts, last_attempt) VALUES (?, 1, NOW())
                    ON DUPLICATE KEY UPDATE attempts = attempts + 1, last_attempt = NOW()", [$ip]);
        echo json_encode(['success' => false, 'message' => 'Invalid login or password']);
        exit;
    }
}

$db->query("DELETE FROM login_attempts WHERE ip = ?", [$ip]);

echo json_encode(['success' => true, 'message' => 'Logged in successfully']);


?>