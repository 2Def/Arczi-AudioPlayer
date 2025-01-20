<?php
if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not allowed.');
}

require_once '../mysql.php';
require_once '../config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = intval($_GET['id']);

try {
    $db = new Database();
    
    $query = "SELECT avatar_image_name FROM book_catalog WHERE id = ?";
    $result = $db->query($query, [$id]);

    if ($result->num_rows === 0) {
        header("Location: dashboard.php");
        exit;
    }

    $chapter = $db->query("SELECT filename FROM book_chapters WHERE book_id = ?", [$id]);

    if ($chapter->num_rows === 0) {
        echo "Brak rozdziałów dla książki o ID: $id";
        var_dump($id);
    }

    while ($currentChapter = $chapter->fetch_assoc())
    {
        $filePath = 'uploads/chapters/' . $currentChapter['filename'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $db->query("DELETE FROM book_chapters WHERE book_id = ?", [$id]);

    $book = $result->fetch_assoc();
    $imageFile = 'uploads/covers/' . $book['avatar_image_name'];

    $deleteQuery = "DELETE FROM book_catalog WHERE id = ?";
    $affectedRows = $db->query($deleteQuery, [$id]);

    if ($affectedRows > 0) {
        if (file_exists($imageFile)) {
            unlink($imageFile);
        }
    }

    // DELETE CHAPTERS
    $db->close();

    header("Location: dashboard.php");
    exit;
} catch (Exception $e) {
    error_log("Error deleting book: " . $e->getMessage());
    header("Location: dashboard.php?error=deletion_failed");
    exit;
}

?>
