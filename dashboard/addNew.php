<?php
if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not allowed.');
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<h1>Dodaj nową ksiązkę</h1>
<p>Uzupełnij formularz, dodaj okładkę a ksiązka pojawi się w bazie. Następnie przejdź do dodanej ksiązki i wrzuć rozdziały.</p>
<p>Każdy plik osobno!</p>

<div class="form-container">           
    <form id="uploadForm" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Tytuł książki:</label>
            <input type="text" name="title" id="title" required>
        </div>
        <div class="form-group">
            <label for="short_description">Krótki opis:</label>
            <textarea name="short_description" id="short_description" required></textarea>
        </div>
        <div class="form-group">
            <label for="cover">Cover Image</label>
            <input type="file" name="cover" id="cover" accept="image/*" required>
        </div>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <button type="submit" class="btn">Dodaj</button>
    </form>
    <div id="message"></div>
    <div id="progress-container">
        <progress id="progressBar" value="0" max="100"></progress>
        <span id="progressText"></span>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#uploadForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            const progressContainer = $('#progress-container');
            progressContainer.show();

            $.ajax({
                url: 'formAddNewBook.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                xhr: function () {
                    const xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function (e) {
                        if (e.lengthComputable) {
                            const percentComplete = (e.loaded / e.total) * 100;
                            $('#progressBar').val(percentComplete);
                            $('#progress-text').text(Math.round(percentComplete) + '%');
                        }
                    });
                    return xhr;
                },
                success: function (response) {
                    $('#message').html('<p>' + response.message + '</p>');
                    $('#message').append('<p>ID: ' + response.id + '</p>');
                    if (response.success) {
                        $('#uploadForm')[0].reset();
                        $('#progressBar').val(0);
                        $('#progress-text').text('');
                        progressContainer.hide();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#message').html('<p>Wystąpił błąd podczas dodawania wpisu.</p>');
                    
                    // DEV:
                    /*
                    $('#message').append('<p>Status: ' + textStatus + '</p>');
                    $('#message').append('<p>Opis: ' + errorThrown + '</p>');
                    $('#message').append('<p>Odpowiedź serwera: ' + jqXHR.responseText + '</p>');
                    */
                }
            });
        });
    });
</script>
