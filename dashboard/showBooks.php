<?php

if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not allowed.');
}

require_once '../mysql.php';

function truncateText($text, $maxLength = 100) {
    if (strlen($text) > $maxLength) {
        return substr($text, 0, $maxLength) . '...';
    }
    return $text;
}

$db = new Database();
$query = "SELECT id, title, avatar_image_name, short_description FROM book_catalog ORDER BY id DESC";
$result = $db->query($query);

if ($result->num_rows > 0):
?>
    <center><h1>Lista dodanych audiobooków</h1></center>
<div class="book-container">
    <?php while ($book = $result->fetch_assoc()):
        $chapters = $db->query("SELECT id FROM book_chapters WHERE book_id = ? ORDER BY chapter_number ASC", [$book['id']])->fetch_all(MYSQLI_ASSOC); 
    ?>
        <div class="book">
            <div class="book-image">
                <img src="uploads/covers/<?php echo htmlspecialchars($book['avatar_image_name']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
            </div>
            <div class="book-info">
                <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                <p><?php echo htmlspecialchars(truncateText($book['short_description'], 150)); ?></p>
                <p><strong>Ilość rozdziałów:</strong> <?php echo count($chapters); ?></p>
                <div class="book-actions">
                    <a href="dashboard.php?page=editBook&id=<?php echo $book['id']; ?>" class="edit-button">EDIT</a>
                    <a href="dashboard.php?page=deleteBook&id=<?php echo $book['id']; ?>" class="delete-button" onclick="return confirm('Czy na pewno chcesz usunąć tę książkę?');">DELETE</a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php else: ?>
    <center><h1>Brak książek w katalogu./h1></center>
<?php endif; ?>
