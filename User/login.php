<?php

define('ACCESS_ALLOWED', true);
define('MODULES', 'modules/');
session_start();

require(MODULES . 'checkBlock.php');
require(MODULES . 'checkLogin.php');
require(MODULES . 'csrfToken.php');
$error = null;

require_once '../mysql.php';
$config = include('../config.php');

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Nieprawidłowy token CSRF!");
}

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
            header("Location: /user/index.php");

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
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Logowanie - Audiobooki</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <?php require_once('modules/nav.php'); ?>
  <div class="container">
  <div class="header-container">
      <h1>Logowanie</h1>
  </div>
    <form  method="POST" action="">
      <label for="username">Nazwa użytkownika:</label>
      <input type="text" id="username" name="username" placeholder="Wpisz nazwę użytkownika" required>

      <label for="password">Hasło:</label>
      <input type="password" id="password" name="password" placeholder="Wpisz hasło" required>

      <div class="checkbox-container">
          <input type="checkbox" name="remember_me" id="remember_me">
          <label for="remember_me">Pamiętaj mnie</label>
      </div>
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
      <button type="submit">Zaloguj się</button>
      <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
  </form>

  </div>
</body>
</html>
