<?php

header('Content-Type: application/json');
define('ACCESS_ALLOWED', true);
session_start();
require_once '../mysql.php';

if (!isset($_GET['book_id']) || !is_numeric($_GET['book_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe ID książki.']);
    exit;
}

$bookId = (int)$_GET['book_id'];
$db = new Database();

$chapters = $db->query("SELECT * FROM book_chapters WHERE book_id = ? ORDER BY chapter_number ASC", [$bookId])->fetch_all(MYSQLI_ASSOC);

echo json_encode(['success' => true, 'chapters' => $chapters]);

?>