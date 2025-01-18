<?php

if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not allowed.');
}

session_unset();
session_destroy();

setcookie('remember_me', '', time() - 3600, "/");
$config = include('../config.php');
$apiKeyBase64 = base64_encode($config['api']['key']);
header("Location: /admin.php?api=".$apiKeyBase64);
exit;

?>