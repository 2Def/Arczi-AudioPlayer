<?php
header('Content-Type: application/json');
define('ACCESS_ALLOWED', true);
session_start();
require_once '../mysql.php';

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowy token CSRF.']);
    exit;
}

if (!isset($_POST['order']) || !is_array($_POST['order'])) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe dane wejściowe.']);
    exit;
}

$order = $_POST['order'];
$db = new Database();

foreach ($order as $index => $chapterId) {
    $chapterId = (int)$chapterId;
    $newOrder = $index + 1;
    $db->query("UPDATE book_chapters SET chapter_number = ? WHERE id = ?", [$newOrder, $chapterId]);
}

echo json_encode(['success' => true]);
