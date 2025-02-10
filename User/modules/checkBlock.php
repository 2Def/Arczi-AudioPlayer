<?php

if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not allowed.');
}

define('LOCK_TIME', 3600); 
define('MAX_ATTEMPTS', 5); 

if (isset($_SESSION['block_time']) && time() - $_SESSION['block_time'] < LOCK_TIME) {
    die('You are temporarily blocked. Please try again later.');
} elseif (isset($_SESSION['block_time']) && time() - $_SESSION['block_time'] >= LOCK_TIME) {
    unset($_SESSION['attempts']);
    unset($_SESSION['block_time']);
}

?>