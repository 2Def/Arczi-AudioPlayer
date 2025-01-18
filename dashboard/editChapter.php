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

<div class="form-container" style="max-width: 700px;">
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

    <div id="progress-container">
        <progress id="progressBar" value="0" max="100"></progress>
        <span id="progressText">0/0</span>
    </div>
<br /><hr /><br />
    <h3>Lista rozdziałów</h3>
    <ul id="chapter-list" class="sortable"></ul>

    <br /><hr /><br />

    <div id="mp3-player" class="mp3-player">
        <audio id="audio" controls>
            Twoja przeglądarka nie obsługuje elementu audio.
        </audio>
    </div>
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
                            <button class="load-chapter" data-filename="${chapter.filename}">Załaduj</button>
                        </li>
                    `);
                });
                makeSortable();
            }
        });
    }

    $('#uploadForm').on('submit', function (e) {
        e.preventDefault();

        const files = $('#files')[0].files; // Pobierz pliki
        const totalFiles = files.length; // Łączna liczba plików
        let uploadedFiles = 0; // Licznik przesłanych plików
        const progressBar = $('#progressBar'); // Pasek postępu
        const progressText = $('#progressText'); // Licznik postępu
        const progressContainer = $('#progress-container');

        if (totalFiles === 0) {
            alert('Nie wybrano plików do przesłania.');
            return;
        }

        progressBar.val(0); // Zresetuj pasek postępu
        progressText.text(`0/${totalFiles}`); // Zresetuj licznik
        progressContainer.show();

        // Iteracja po plikach i ich przesyłanie
        Array.from(files).forEach((file, index) => {
            let formData = new FormData();
            formData.append('files[]', file);
            formData.append('csrf_token', $('input[name="csrf_token"]').val());
            formData.append('book_id', $('#book_id').val());

            $.ajax({
                url: 'uploadChapter.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                xhr: function () {
                    const xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function (e) {
                        if (e.lengthComputable) {
                            const percentComplete = (e.loaded / e.total) * 100;
                            progressBar.val(((uploadedFiles + percentComplete / 100) / totalFiles) * 100); // Aktualizacja wspólnego paska
                        }
                    });
                    return xhr;
                },
                success: function (response) {
                    if (response.success) {
                        uploadedFiles++; // Zwiększ licznik przesłanych plików
                        progressText.text(`${uploadedFiles}/${totalFiles}`); // Aktualizuj tekst
                    } else {
                        alert(`Błąd podczas przesyłania pliku: ${file.name}`);
                    }

                    if (uploadedFiles === totalFiles) {
                        progressContainer.hide();
                        loadChapters();
                    }
                },
                error: function () {
                    alert(`Wystąpił błąd podczas przesyłania pliku: ${file.name}`);
                }
            });
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

    // Włączanie playera
    $('#chapter-list').on('click', '.load-chapter', function () {
        const fileSource = $(this).data('filename');
        const targetElementNew = $('#mp3-player');
        targetElementNew.show();

        $('html, body').animate({
            scrollTop: $('#mp3-player').offset().top
        }, 1000);
        
        $('#audio').attr('src', 'uploads/chapters/'+fileSource);
        $('#audio')[0].load(); 
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

    loadChapters();
});
</script>