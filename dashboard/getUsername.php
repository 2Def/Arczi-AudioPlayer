<?php

function GetUsername()
{
    $config = include('../config.php');
    
    if (isset($_SESSION['username'])) {
        return $_SESSION['username'];
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
            return $username;
        } else {
            setcookie('remember_me', '', time() - 3600, "/");
            return false;
        }
    } else {
        return false;
    }
}
?>