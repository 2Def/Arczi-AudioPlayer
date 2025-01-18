<?php
header('Content-Type: application/json');
define('ACCESS_ALLOWED', true);
session_start();
require_once '../mysql.php';

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowy token CSRF.']);
    die("Nieprawidłowy token CSRF!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();

    $title = trim($_POST['title'] ?? '');
    $short_description = trim($_POST['short_description'] ?? '');
    $cover = $_FILES['cover'] ?? null;

    if (empty($title) || empty($short_description) || !$cover) {
        echo json_encode(['success' => false, 'message' => 'Wszystkie pola są wymagane.']);
        exit;
    }

    $uploadDir = 'uploads/covers/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileExtension = pathinfo($cover['name'], PATHINFO_EXTENSION);
    $randomFileName = uniqid('cover_', true) . '.' . $fileExtension;
    $targetFilePath = $uploadDir . $randomFileName;

    if (move_uploaded_file($cover['tmp_name'], $targetFilePath)) {
        $query = "INSERT INTO book_catalog (title, avatar_image_name, short_description) VALUES (?, ?, ?)";
        $params = [$title, $randomFileName, $short_description];

        $insertId = $db->query($query, $params, true); 
        if ($insertId > 0) { 
            echo json_encode(['success' => true, 'message' => 'Wpis został dodany pomyślnie.', 'id' => $insertId]);
        } else {
            unlink($targetFilePath);  // Usuwamy plik, jeśli nie udało się dodać rekordu
            echo json_encode(['success' => false, 'message' => 'Nie udało się zapisać wpisu do bazy.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Nie udało się przesłać pliku.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe żądanie.']);
}
?>
