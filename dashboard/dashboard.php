<?php
define('ACCESS_ALLOWED', true);
session_start();

require_once '../mysql.php';
$config = include('../config.php');

$apiKeyBase64 = base64_encode($config['api']['key']);

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} elseif (isset($_COOKIE['remember_me'])) {
    $cookieValue = base64_decode($_COOKIE['remember_me']);
    list($cookieUsername, $cookieHash) = explode('|', $cookieValue);

    $expectedHash = hash_hmac('sha256', $cookieUsername, $config['api']['key']);

    if (hash_equals($expectedHash, $cookieHash)) {
        $username = $cookieUsername;
        setcookie(
            'remember_me',
            base64_encode("$cookieUsername|$expectedHash"),
            time() + (30 * 24 * 60 * 60),
            "/", "", true, true
        );
    } else {
        setcookie('remember_me', '', time() - 3600, "/");
        header("Location: /admin.php?api=" . $apiKeyBase64);
        exit;
    }
} else {
    echo "ERROR";
    header("Location: /admin.php?api=" . $apiKeyBase64);
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function GetPage()
{
    if(!isset($_GET['page']))
    {
        include 'showBooks.php';
    }
    else
    {
        $page = $_GET["page"];
        switch ($page) {
            case 'logout':
                include 'logout.php';
                break;
            case 'addNew':
                include 'addNew.php';
                break;
            case 'deleteBook':
                include 'deleteBook.php';
                break;
            case 'editBook':
                include 'editBook.php';
                break;
            case 'editChapter':
                include 'editChapter.php';
                break;
            default:
                echo '<h3>Błąd</h3><p>Wybrana strona nie istnieje.</p>';
                break;
        }
    }
}

function GetPageTitle()
{
    if(!isset($_GET['page']))
    {
        echo 'Dashboard';
    }
    else
    {
        switch ($_GET["page"]) {
            case 'logout':
                echo 'Wyloguj';
                break;
            case 'addNew':
                echo "Dodaj nową ksiązkę";
                break;
            case 'editBook':
                echo "Edytuj ksiązkę";
                break;
            case 'editChapter':
                echo 'Edycja rozdziałów';
                break;
            default:
                echo 'Dashboard';
                break;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <title>Arczi AudioPlayer - <?php GetPageTitle(); ?></title>
</head>
<body>
    <div class="top-bar">
        <h2>Witaj, <?= htmlspecialchars($username) ?>!</h2>
        <a href="dashboard.php?page=logout">Wyloguj</a>
    </div>
    <div class="container">
        <nav>
            <h2>Nawigacja</h2>
            <a href="dashboard.php">Głowna - Lista książek</a>
            <a href="dashboard.php?page=addNew">Dodaj nową książkę</a>
            <a href="strona2.html">Przycisk 2</a>
            <a href="strona3.html">Przycisk 3</a>
            <a href="strona4.html">Przycisk 4</a>
        </nav>
        <div class="content">
            <?php GetPage(); ?>
        </div>
    </div>
</body>
</html>

