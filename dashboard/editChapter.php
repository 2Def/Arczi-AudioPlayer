<?php
if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not allowed.');
}

require_once '../mysql.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Nieprawidłowe ID książki.');
}
$bookId = (int)$_GET['id'];

$db = new Database();
$chapters = $db->query("SELECT * FROM book_chapters WHERE book_id = ? ORDER BY chapter_number ASC", [$bookId])->fetch_all(MYSQLI_ASSOC);
?>

<div class="form-container">
    <h2>Zarządzanie rozdziałami</h2>
    <form id="uploadForm" enctype="multipart/form-data">
        <div class="form-group">
            <label for="files">Wybierz pliki (MP3):</label>
            <input type="file" name="files[]" id="files" multiple accept="audio/mpeg">
        </div>
        <input type="hidden" name="csrf_token" id="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="book_id" id="book_id" value="<?= htmlspecialchars($_GET['id'] ?? 0) ?>">
        <button type="submit" class="btn">Dodaj rozdziały</button>
    </form>

    <div id="message"></div>

    <h3>Lista rozdziałów</h3>
    <ul id="chapter-list" class="sortable"></ul>
</div>

<script>
$(document).ready(function () {
    const csrfToken = $('#csrf_token').val();
    const bookId = $('#book_id').val();

    // Pobranie istniejących rozdziałów
    function loadChapters() {
        $.get('getChapters.php', { book_id: bookId }, function (response) {
            if (response.success) {
                const list = $('#chapter-list').empty();
                response.chapters.forEach(chapter => {
                    list.append(`
                        <li data-id="${chapter.id}" class="chapter-item">
                            <span class="chapter-number">Rozdział ${chapter.chapter_number}</span>
                            <input type="text" class="chapter-title" value="${chapter.title}">
                            <button class="delete-chapter" data-id="${chapter.id}">Usuń</button>
                        </li>
                    `);
                });
                makeSortable();
            }
        });
    }

    // Wysyłanie plików
    $('#uploadForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('csrf_token', csrfToken);
        formData.append('book_id', bookId);

        $.ajax({
            url: 'uploadChapter.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#message').text(response.message || 'Dodano rozdziały.');
                loadChapters();
            }
        });
    });

    // Usuwanie rozdziałów
    $('#chapter-list').on('click', '.delete-chapter', function () {
        const id = $(this).data('id');
        $.post('deleteChapter.php', { id, csrf_token: csrfToken }, function (response) {
            if (response.success) {
                loadChapters();
            } else {
                alert('Nie udało się usunąć rozdziału.');
            }
        });
    });

    // Aktualizacja tytułu rozdziału
    $('#chapter-list').on('change', '.chapter-title', function () {
        const id = $(this).closest('li').data('id');
        const title = $(this).val();
        $.post('updateChapter.php', { id, title, csrf_token: csrfToken }, function (response) {
            if (!response.success) {
                alert('Nie udało się zaktualizować tytułu.');
            }
        });
    });

    // Sortowanie rozdziałów
    function makeSortable() {
        $('#chapter-list').sortable({
            update: function () {
                const order = [];
                $('#chapter-list .chapter-item').each(function () {
                    order.push($(this).data('id'));
                });
                $.post('reorderChapters.php', { order, csrf_token: csrfToken }, function (response) {
                    if (response.success) {
                        loadChapters();
                    } else {
                        alert('Nie udało się zaktualizować kolejności.');
                    }
                });
            }
        });
    }

    // Początkowe wczytanie listy
    loadChapters();
});
</script>