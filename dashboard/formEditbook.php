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

    $id = intval($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $short_description = trim($_POST['short_description'] ?? '');
    $cover = $_FILES['cover'] ?? null;

    if ($id <= 0 || empty($title) || empty($short_description)) {
        echo json_encode(['success' => false, 'message' => 'Wszystkie pola (poza okładką) są wymagane.']);
        exit;
    }

    $query = "SELECT avatar_image_name FROM book_catalog WHERE id = ?";
    $book = $db->query($query, [$id])->fetch_assoc();
    if (!$book) {
        echo json_encode(['success' => false, 'message' => 'Nie znaleziono książki do edycji.']);
        exit;
    }

    $currentImage = $book['avatar_image_name'];
    $newImage = $currentImage;

    if ($cover && $cover['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/covers/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileExtension = pathinfo($cover['name'], PATHINFO_EXTENSION);
        $randomFileName = uniqid('cover_', true) . '.' . $fileExtension;
        $targetFilePath = $uploadDir . $randomFileName;

        if (move_uploaded_file($cover['tmp_name'], $targetFilePath)) {
            $newImage = $randomFileName;

            if ($currentImage && file_exists($uploadDir . $currentImage)) {
                unlink($uploadDir . $currentImage);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Nie udało się przesłać nowej okładki.']);
            exit;
        }
    }

    $updateQuery = "UPDATE book_catalog SET title = ?, avatar_image_name = ?, short_description = ? WHERE id = ?";
    $params = [$title, $newImage, $short_description, $id];
    $affectedRows = $db->query($updateQuery, $params);

    $db->close();

    if ($affectedRows > 0) {
        $response = ['success' => true, 'message' => 'Książka została pomyślnie zaktualizowana.'];
        if (!empty($newImage)) {
            $response['new_cover'] = $newImage;
        }
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'Nie wprowadzono żadnych zmian.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe żądanie.']);
}
?>
