<?php
header('Content-Type: application/json');
define('ACCESS_ALLOWED', true);
session_start();
require_once '../mysql.php';

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowy token CSRF.']);
    exit;
}

if (!isset($_POST['id']) || !is_numeric($_POST['id']) || !isset($_POST['title'])) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe dane wejściowe.']);
    exit;
}

$chapterId = (int)$_POST['id'];
$title = trim($_POST['title']);
$db = new Database();

$db->query("UPDATE book_chapters SET title = ? WHERE id = ?", [$title, $chapterId]);

echo json_encode(['success' => true]);
