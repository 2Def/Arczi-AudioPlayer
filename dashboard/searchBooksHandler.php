<?php
header('Content-Type: application/json');
define('ACCESS_ALLOWED', true);

session_start();

require_once '../mysql.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe żądanie.']);
    exit;
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowy token CSRF.']);
    exit;
}

if (!isset($_POST['search'])) {
    echo json_encode(['success' => false, 'message' => 'Brak parametru wyszukiwania.']);
    exit;
}

function truncateText($text, $maxLength = 100) {
    if (strlen($text) > $maxLength) {
        return substr($text, 0, $maxLength) . '...';
    }
    return $text;
}


$searchTerm = trim($_POST['search']);
$db = new Database();

// Wyszukiwanie w bazie danych
$query = "SELECT id, title, avatar_image_name, short_description 
          FROM book_catalog 
          WHERE title LIKE ? OR short_description LIKE ? 
          ORDER BY id DESC";
$searchParam = "%" . $searchTerm . "%";
$result = $db->query($query, [$searchParam, $searchParam]);

// Generowanie HTML dla wyników
if ($result->num_rows > 0) {
    ob_start(); // Buforowanie wyniku
    ?>
    <div class="book-container">
        <?php while ($book = $result->fetch_assoc()): ?>
            <div class="book">
                <div class="book-image">
                    <img src="uploads/covers/<?php echo htmlspecialchars($book['avatar_image_name']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                </div>
                <div class="book-info">
                    <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                    <p><?php echo htmlspecialchars(truncateText($book['short_description'], 150)); ?></p>
                    <div class="book-actions">
                        <a href="dashboard.php?page=editBook&id=<?php echo $book['id']; ?>" class="edit-button">EDIT</a>
                        <a href="dashboard.php?page=deleteBook&id=<?php echo $book['id']; ?>" class="delete-button" onclick="return confirm('Czy na pewno chcesz usunąć tę książkę?');">DELETE</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php
    $html = ob_get_clean(); // Pobierz wygenerowany HTML
    echo json_encode(['success' => true, 'html' => $html]);
} else {
    echo json_encode(['success' => true, 'html' => '<center><h1>Brak wyników wyszukiwania.</h1></center>']);
}
exit;
