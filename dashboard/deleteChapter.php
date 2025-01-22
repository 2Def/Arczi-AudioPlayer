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

$chapter = $db->query("SELECT * FROM book_chapters WHERE id = ?", [$chapterId])->fetch_assoc();
if ($chapter) {
    $filePath = 'uploads/chapters/' . $chapter['filename'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

$chapterNumber = $chapter['chapter_number'];
$bookId = $chapter['book_id'];

$db->query("DELETE FROM book_chapters WHERE id = ?", [$chapterId]);
$db->query("UPDATE book_chapters SET chapter_number = chapter_number - 1 WHERE chapter_number > ? AND book_id = ?", [$chapterNumber, $bookId]);

echo json_encode(['success' => true]);

?>