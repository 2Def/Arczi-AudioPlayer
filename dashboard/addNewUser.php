<?php
if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not allowed.');
}

?>

<h1>Dodaj nowego użytkownika</h1>
<div class="form-container">
    <form id="add-user-form">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="form-group">
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div class="form-group">
            <label for="password">Hasło:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="courolentry">Ranga użytkownika:</label>
            <select id="role" name="role" required>>
                <option value="1">Użytkownik</option>
                <option value="2">Moderator</option>
                <option value="3">Administrator</option>
            </select>
        </div>
        <button type="submit">Dodaj użytkownika</button>
    </form>
    <div id="message" class="message"></div>
</div>

<script>
        document.getElementById('add-user-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const messageDiv = document.getElementById('message');

            fetch('addUserHandler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.textContent = 'Użytkownik został pomyślnie dodany.';
                    messageDiv.className = 'message success';
                    messageDiv.style.display = 'block';

                    setTimeout(() => {
                        window.location.href = 'dashboard.php?page=showUsers';
                    }, 2000);
                } else {
                    messageDiv.textContent = data.message || 'Wystąpił błąd podczas dodawania użytkownika.';
                    messageDiv.className = 'message error';
                    messageDiv.style.display = 'block';
                }
            })
            .catch(error => {
                messageDiv.textContent = 'Wystąpił błąd podczas łączenia z serwerem.';
                messageDiv.className = 'message error';
                messageDiv.style.display = 'block';
            });
        });
    </script>