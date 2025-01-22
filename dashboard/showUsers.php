<?php
if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not allowed.');
}

require_once '../mysql.php';

$username = GetUsername();

if (!$username) {
    die('Access denied.');
}

$db = new Database();
$users = $db->query("SELECT id, username, role, registration_date FROM users ORDER BY id ASC");

$roleNames = [
    1 => 'User',
    2 => 'Moderator',
    3 => 'Administrator'
];

?>
<h1>Zarządzanie użytkownikami</h1>
<input type="hidden" id="csrf-token" value="<?php echo $_SESSION['csrf_token']; ?>">
<input type="hidden" id="current-username" value="<?php echo htmlspecialchars($username); ?>">
<table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Registration Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $users->fetch_assoc()): ?>
                <tr id="user-<?php echo $user['id']; ?>">
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo $roleNames[$user['role']] ?? 'Unknown'; ?></td>
                    <td><?php echo $user['registration_date']; ?></td>
                    <td>
                        <div class="action-buttons">
                            <button class="edit-button" data-id="<?php echo $user['id']; ?>">Edit</button>
                            <button class="delete-button" data-id="<?php echo $user['id']; ?>" data-username="<?php echo htmlspecialchars($user['username']); ?>">Delete</button>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="dashboard.php?page=addNewUser" class="info-button">Dodaj użytkownika</a>
</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const csrfToken = document.getElementById('csrf-token').value;
        const currentUsername = document.getElementById('current-username').value;

        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');
                const username = this.getAttribute('data-username');

                if (confirm('Na pewno chcesz usunąć tego użytownika?')) {
                    fetch('deleteUser.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `id=${encodeURIComponent(userId)}&username=${encodeURIComponent(username)}&csrf_token=${encodeURIComponent(csrfToken)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`user-${userId}`).remove();

                            if (username === currentUsername) {
                                alert('Usunąłeś swoje konto. Nastąpi wylogowanie.');
                                window.location.href = 'dashboard.php?page=logout';
                            } else {
                                alert('Użytkownik usunięty prawidłowo.');
                            }
                        } else {
                            alert(data.message || 'An error occurred.');
                        }
                    })
                    .catch(err => console.error(err));
                }
            });
        });
    });
</script>