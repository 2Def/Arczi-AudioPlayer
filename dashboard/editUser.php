<?php
if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not allowed.');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Brak ID użytkownika.');
}

require_once '../mysql.php';

$userId = (int)$_GET['id'];
$db = new Database();

$user = $db->query("SELECT username, role FROM users WHERE id = ?", [$userId])->fetch_assoc();
if (!$user) {
    die('Nie znaleziono użytkownika.');
}

?>
    <h1>Edytuj użytkownika</h1>
    <div class="form-container">
        <form id="edit-user-form">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="id" value="<?php echo $userId; ?>">

            <div class="form-group">
                <label for="username">Nazwa użytkownika:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="password">Nowe hasło (opcjonalne):</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="role">Ranga użytkownika:</label>
                <select id="role" name="role">
                    <option value="1" <?php echo $user['role'] == 1 ? 'selected' : ''; ?>>Użytkownik</option>
                    <option value="2" <?php echo $user['role'] == 2 ? 'selected' : ''; ?>>Moderator</option>
                    <option value="3" <?php echo $user['role'] == 3 ? 'selected' : ''; ?>>Administrator</option>
                </select>
            </div>
            <button type="submit">Zapisz zmiany</button>
        </form>
        <div id="message" class="message"></div>
    </div>

    <script>
        document.getElementById('edit-user-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const messageDiv = document.getElementById('message');

            fetch('editUserHandler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.textContent = 'Dane użytkownika zostały zaktualizowane.';
                    messageDiv.className = 'message success';
                    messageDiv.style.display = 'block';

                    setTimeout(() => {
                        window.location.href = 'dashboard.php?page=showUsers';
                    }, 2000);
                } else {
                    messageDiv.textContent = data.message || 'Wystąpił błąd podczas aktualizacji użytkownika.';
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