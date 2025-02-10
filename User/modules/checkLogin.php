<?php

if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not allowed.');
}

if (isset($_SESSION['username']) || isset($_COOKIE['remember_me'])) {
    header("Location: /user/index.php");
    exit;
}
    
?>