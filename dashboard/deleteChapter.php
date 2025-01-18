<?php
header('Content-Type: application/json');
define('ACCESS_ALLOWED', true);
session_start();
require_once '../mysql.php';

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowy token CSRF.']);
    exit;
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe ID rozdziału.']);
    exit;
}

$chapterId = (int)$_POST['id'];
$db = new Database();

// Pobierz informacje o pliku, aby usunąć go z dysku
$chapter = $db->query("SELECT filename FROM book_chapters WHERE id = ?", [$chapterId])->fetch_assoc();
if ($chapter) {
    $filePath = 'uploads/chapters/' . $chapter['filename'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

// Usuń rozdział z bazy danych
$db->query("DELETE FROM book_chapters WHERE id = ?", [$chapterId]);

echo json_encode(['success' => true]);

?>