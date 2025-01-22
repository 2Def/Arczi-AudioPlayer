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
<h1>User Management</h1>
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
                        <button class="delete-button" data-id="<?php echo $user['id']; ?>">Delete</button>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<button id="add-user-button">Add User</button>
