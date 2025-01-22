<?php
define('ACCESS_ALLOWED', true);
session_start();

require_once '../mysql.php';
$config = include('../config.php');

$apiKeyBase64 = base64_encode($config['api']['key']);

require_once 'getUsername.php';

$username = GetUsername();

if (!$username) {
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
            case 'searchBooks':
                include 'searchBooks.php';
                break;
            case 'showUsers':
                include 'showUsers.php';
                break;
            case 'addNewUser':
                include 'addNewUser.php';
                break;
            case 'editUser':
                include 'editUser.php';
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
            case 'searchBooks':
                echo 'Szukaj książek';
                break;
            case 'showUsers':
                echo 'Pokaż użytkowników';
                break;
            case 'addNewUser':
                echo 'Dodaj użytkownika';
                break;
            case 'editUser':
                echo 'Edytuj użytkownika';
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
            <a href="dashboard.php?page=searchBooks">Szukaj książek</a>
            <a href="dashboard.php?page=showUsers">Lista użytkowników</a>
            <a href="strona4.html">Przycisk 4</a>
        </nav>
        <div class="content">
            <?php GetPage(); ?>
        </div>
    </div>
</body>
</html>

