<?php
header('Content-Type: application/json');
define('ACCESS_ALLOWED', true);
session_start();
require_once '../mysql.php';

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowy token CSRF.']);
    exit;
}

if (!isset($_POST['username'], $_POST['password'], $_POST['role']) || 
    empty($_POST['username']) || empty($_POST['password']) || empty($_POST['role'])) {
    echo json_encode(['success' => false, 'message' => 'Brak wymaganych danych.']);
    exit;
}

$username = trim($_POST['username']);
$password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
$role = (int)$_POST['role'];

$db = new Database();

$existingUser = $db->query("SELECT id FROM users WHERE username = ?", [$username])->fetch_assoc();
if ($existingUser) {
    echo json_encode(['success' => false, 'message' => 'Nazwa użytkownika już istnieje.']);
    exit;
}

$insert = $db->query("INSERT INTO users (username, password, role, registration_date) VALUES (?, ?, ?, NOW())", [$username, $password, $role]);

if ($insert) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Wystąpił błąd podczas dodawania użytkownika.']);
}
