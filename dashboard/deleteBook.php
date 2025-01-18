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

    $book = $result->fetch_assoc();
    $imageFile = 'uploads/covers/' . $book['avatar_image_name'];

    $deleteQuery = "DELETE FROM book_catalog WHERE id = ?";
    $affectedRows = $db->query($deleteQuery, [$id]);

    if ($affectedRows > 0) {
        if (file_exists($imageFile)) {
            unlink($imageFile);
        }
    }

    $db->close();

    header("Location: dashboard.php");
    exit;
} catch (Exception $e) {
    error_log("Error deleting book: " . $e->getMessage());
    header("Location: dashboard.php?error=deletion_failed");
    exit;
}

?>
