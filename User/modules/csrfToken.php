<?php

if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not allowed.');
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>