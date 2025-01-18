<?php
header('Content-Type: application/json');
define('ACCESS_ALLOWED', true);
session_start();
require_once '../mysql.php';
require_once '../getid3/getid3.php';


if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowy token CSRF.']);
    die("Nieprawidłowy token CSRF!");
}

if (!isset($_POST['book_id']) || !is_numeric($_POST['book_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe ID książki.']);
    exit;
}

$bookId = (int)$_POST['book_id'];
$db = new Database();

if (!isset($_FILES['files']) || empty($_FILES['files']['name'][0])) {
    echo json_encode(['success' => false, 'message' => 'Brak plików do przesłania.']);
    exit;
}

$uploadDir = 'uploads/chapters/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$uploadedFiles = [];
foreach ($_FILES['files']['name'] as $key => $name) {
    $tmpName = $_FILES['files']['tmp_name'][$key];
    $fileExtension = pathinfo($name, PATHINFO_EXTENSION);
    $randomFileName = uniqid('chapter_', true) . '.' . $fileExtension;
    $targetFilePath = $uploadDir . $randomFileName;

    if (move_uploaded_file($tmpName, $targetFilePath)) {
        $duration = 0;
        
        if ($fileExtension === 'mp3') {
            $getID3 = new getID3;
            $fileInfo = $getID3->analyze($targetFilePath);

            if (isset($fileInfo['playtime_seconds'])) {
                $duration = $fileInfo['playtime_seconds'];
            }
        }

        $lastChapter = $db->query("SELECT MAX(chapter_number) AS max_number FROM book_chapters WHERE book_id = ?", [$bookId])->fetch_assoc();
        $nextChapterNumber = ($lastChapter['max_number'] ?? 0) + 1;

        $db->query("INSERT INTO book_chapters (book_id, chapter_number, title, duration, filename) VALUES (?, ?, ?, ?, ?)", [
            $bookId,
            $nextChapterNumber,
            $name, // Default title is the filename
            $duration,
            $randomFileName
        ]);

        $uploadedFiles[] = $name;
    }
}

echo json_encode([
    'success' => true,
    'uploaded_files' => $uploadedFiles,
    'total_files' => count($uploadedFiles)
]);

