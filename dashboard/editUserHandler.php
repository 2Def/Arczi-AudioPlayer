<?php
header('Content-Type: application/json');
define('ACCESS_ALLOWED', true);

session_start();

require_once '../mysql.php';

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowy token CSRF.']);
    exit;
}

if (!isset($_POST['id'], $_POST['role']) || !is_numeric($_POST['id']) || !is_numeric($_POST['role'])) {
    echo json_encode(['success' => false, 'message' => 'Brak wymaganych danych.']);
    exit;
}

$userId = (int)$_POST['id'];
$role = (int)$_POST['role'];
$password = isset($_POST['password']) && !empty(trim($_POST['password'])) 
    ? password_hash(trim($_POST['password']), PASSWORD_BCRYPT) 
    : null;

$db = new Database();

$existingUser = $db->query("SELECT username FROM users WHERE id = ?", [$userId])->fetch_assoc();
if (!$existingUser) {
    echo json_encode(['success' => false, 'message' => 'Nie znaleziono użytkownika.']);
    exit;
}

if ($password) {
    $update = $db->query("UPDATE users SET password = ?, role = ? WHERE id = ?", [$password, $role, $userId]);
} else {
    $update = $db->query("UPDATE users SET role = ? WHERE id = ?", [$role, $userId]);
}

if ($update) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Wystąpił błąd podczas aktualizacji danych.']);
}
