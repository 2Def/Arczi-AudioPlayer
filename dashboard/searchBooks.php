<?php

if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not allowed.');
}
?>

<div class="search-container">
    <input type="text" id="search-input" placeholder="Wyszukaj książkę...">
    <input type="hidden" id="csrf-token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
</div>

<div id="book-results" class="book-container">
    <!-- Tutaj będą wyświetlane wyniki wyszukiwania -->
</div>

<script>
    
document.getElementById('search-input').addEventListener('input', function () {
    const searchTerm = this.value;
    const csrfToken = document.getElementById('csrf-token').value;

    // Wykonaj żądanie AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'searchBooksHandler.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                document.getElementById('book-results').innerHTML = response.html;
            } else {
                alert(response.message);
            }
        } else {
            console.error('Błąd w komunikacji z serwerem.');
        }
    };

    xhr.send('search=' + encodeURIComponent(searchTerm) + '&csrf_token=' + encodeURIComponent(csrfToken));
});

</script>