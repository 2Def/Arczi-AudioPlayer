<?php
define('ACCESS_ALLOWED', true);
session_start();

define('MAX_ATTEMPTS', 5); 
define('LOCK_TIME', 3600); 

$error = null;

if (isset($_SESSION['block_time']) && time() - $_SESSION['block_time'] < LOCK_TIME) {
    die('You are temporarily blocked. Please try again later.');
} elseif (isset($_SESSION['block_time']) && time() - $_SESSION['block_time'] >= LOCK_TIME) {
    unset($_SESSION['attempts']);
    unset($_SESSION['block_time']);
}

if (isset($_SESSION['username']) || isset($_COOKIE['remember_me'])) {
    header("Location: /dashboard/dashboard.php");
    exit;
}

require_once 'mysql.php';
$config = include('config.php');
$apiKeyBase64 = base64_encode($config['api']['key']);

if (!isset($_GET['api']) || $_GET['api'] !== $apiKeyBase64) {

    if (!isset($_SESSION['attempts'])) {
        $_SESSION['attempts'] = 0;
    }

    $_SESSION['attempts']++;
    
    if ($_SESSION['attempts'] >= MAX_ATTEMPTS) {
        $_SESSION['block_time'] = time();
        die('You have exceeded the maximum number of attempts. Please try again after 1 hour.');
    }

    die('Invalid API Key. You have ' . (MAX_ATTEMPTS - $_SESSION['attempts']) . ' attempt(s) remaining.');
}

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['remember_me']);

    $sql = "SELECT * FROM users WHERE username = ?";
    $result = $db->query($sql, [$username]);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            
            if ($rememberMe) {
                setcookie('remember_me', 
                    base64_encode("$username|".hash_hmac('sha256', $username, $config['api']['key'])), 
                    time() + (30 * 24 * 60 * 60), 
                    "/", "", true, true);
            } else {
                $_SESSION['username'] = $username;
            }
            $_SESSION['attempts'] = 0;
            header("Location: /dashboard/dashboard.php");

        } else {
            $error = 'Invalid username or password.';
            $_SESSION['attempts']++; 
        }
    } else {
        $error = 'Invalid username or password.';
        $_SESSION['attempts']++; 
    }

    if ($_SESSION['attempts'] >= MAX_ATTEMPTS) {
        $_SESSION['block_time'] = time();
        die('You have exceeded the maximum number of attempts. Please try again after 1 hour.');
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <title>Arczi AudioPlayer - Dashboard login</title>
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group checkbox-group">
                <input type="checkbox" name="remember_me" id="remember_me">
                <label for="remember_me">Remember me</label>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>
