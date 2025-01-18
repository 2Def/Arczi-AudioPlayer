<?php 

if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not allowed.');
}

require_once '../mysql.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Nieprawidłowy identyfikator książki.");
}

$id = intval($_GET['id']);

try {
    $db = new Database();

    $query = "SELECT title, avatar_image_name, short_description FROM book_catalog WHERE id = ?";
    $result = $db->query($query, [$id]);

    if ($result->num_rows === 0) {
        die("Książka o podanym ID nie istnieje.");
    }

    $book = $result->fetch_assoc();
} catch (Exception $e) {
    die("Błąd: " . $e->getMessage());
}
?>

<div class="form-container">
<h1>Edytuj książkę</h1>
<form id="editForm" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Tytuł książki:</label>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($book['title']) ?>" required>
    </div>
    <div class="form-group">
        <label for="short_description">Krótki opis:</label>
        <textarea name="short_description" id="short_description" required><?= htmlspecialchars($book['short_description']) ?></textarea>
    </div>
    <div class="form-group">
        <label>Obecna okładka:</label>
        <img id="current-cover" src="uploads/covers/<?= htmlspecialchars($book['avatar_image_name']) ?>" alt="Okładka" style="max-width: 200px; display: block; margin-bottom: 10px;">
        <label for="cover">Nowa okładka (opcjonalnie):</label>
        <input type="file" name="cover" id="cover" accept="image/*">
    </div>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
    <input type="hidden" name="id" value="<?= $id ?>">
    <button type="submit" class="btn">Zapisz zmiany</button>
    <a href="dashboard.php?page=editChapter&id=<?php echo $id; ?>" style="float: right;" class="edit-button">Edytuj rozdziały</a>
</form>
    <div id="message"></div>
    <div id="progress-container">
        <progress id="progressBar" value="0" max="100"></progress>
        <span id="progress-text"></span>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#editForm').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: 'formEditBook.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            xhr: function () {
                const xhr = new XMLHttpRequest();
                xhr.upload.addEventListener('progress', function (e) {
                    if (e.lengthComputable) {
                        $('#progress-container').show();
                        const percentComplete = (e.loaded / e.total) * 100;
                        $('#progressBar').val(percentComplete);
                        $('#progress-text').text(Math.round(percentComplete) + '%');
                    }
                });
                return xhr;
            },
            success: function (response) {
                $('#message').html('<p>' + response.message + '</p>');
                if (response.success) {
                    $('#progress-container').hide();
                    $('#progressBar').val(0);
                    $('#progress-text').text('');

                    if (response.new_cover) {
                        const newCoverPath = 'uploads/covers/' + response.new_cover;
                        $('#current-cover').attr('src', newCoverPath);
                    }

                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#message').html('<p>Wystąpił błąd podczas aktualizacji książki.</p>');
            }
        });
    });
});
</script>