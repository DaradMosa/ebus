<!DOCTYPE html>
<html lang="en">
<?php
include('../config.php');
requireAdmin();

// Handle user operations
$message = '';
$messageType = '';

// Delete user
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    
    // Don't allow deleting the current admin user
    if ($userId != $_SESSION['user_id']) {
        $deleteQuery = "DELETE FROM users WHERE id = $userId";
        if (mysqli_query($conn, $deleteQuery)) {
            $message = 'User deleted successfully';
            $messageType = 'success';
        } else {
            $message = 'Error deleting user: ' . mysqli_error($conn);
            $messageType = 'danger';
        }
    } else {
        $message = 'You cannot delete your own account';
        $messageType = 'danger';
    }
}

// Add new user
if (isset($_POST['add_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $fullName = mysqli_real_escape_string($conn, $_POST['full_name'] ?? '');
    $role = mysqli_real_escape_string($conn, $_POST['role'] ?? 'user');
    
    // Check if username exists
    $checkQuery = "SELECT id FROM users WHERE username = '$username'";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        $message = 'Username already exists';
        $messageType = 'danger';
    } else {
        // Build insert query based on available columns
        $columns = ['username', 'password'];
        $values = ["'$username'", "'$password'"];
        
        // Check and add optional columns
        $checkEmail = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'email'");
        if (mysqli_num_rows($checkEmail) > 0 && !empty($email)) {
            $columns[] = 'email';
            $values[] = "'$email'";
        }
        
        $checkFullName = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'full_name'");
        if (mysqli_num_rows($checkFullName) > 0 && !empty($fullName)) {
            $columns[] = 'full_name';
            $values[] = "'$fullName'";
        }
        
        $checkRole = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'role'");
        if (mysqli_num_rows($checkRole) > 0) {
            $columns[] = 'role';
            $values[] = "'$role'";
        }
        
        $insertQuery = "INSERT INTO users (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ")";
        
        if (mysqli_query($conn, $insertQuery)) {
            $message = 'User added successfully';
            $messageType = 'success';
        } else {
            $message = 'Error adding user: ' . mysqli_error($conn);
            $messageType = 'danger';
        }
    }
}

// Edit user
if (isset($_POST['edit_user'])) {
    $userId = intval($_POST['user_id']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $fullName = mysqli_real_escape_string($conn, $_POST['full_name'] ?? '');
    $role = mysqli_real_escape_string($conn, $_POST['role'] ?? 'user');
    $password = mysqli_real_escape_string($conn, $_POST['password'] ?? '');
    
    $updates = ["username = '$username'"];
    
    // Add optional fields if they exist
    $checkEmail = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'email'");
    if (mysqli_num_rows($checkEmail) > 0) {
        $updates[] = "email = '$email'";
    }
    
    $checkFullName = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'full_name'");
    if (mysqli_num_rows($checkFullName) > 0) {
        $updates[] = "full_name = '$fullName'";
    }
    
    $checkRole = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'role'");
    if (mysqli_num_rows($checkRole) > 0) {
        $updates[] = "role = '$role'";
    }
    
    // Only update password if provided
    if (!empty($password)) {
        $updates[] = "password = '$password'";
    }
    
    $updateQuery = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = $userId";
    
    if (mysqli_query($conn, $updateQuery)) {
        $message = 'User updated successfully';
        $messageType = 'success';
    } else {
        $message = 'Error updating user: ' . mysqli_error($conn);
        $messageType = 'danger';
    }
}

// Get all users
$usersQuery = "SELECT * FROM users ORDER BY id DESC";
$usersResult = mysqli_query($conn, $usersQuery);

include('head.php');
?>
<body>
    <?php include('nav.php'); ?>
    
    <div class="admin-container">
        <div class="admin-content">
            <div class="page-header">
                <h1><i class="fa fa-users"></i> User Management</h1>
                <p>Manage all registered users and their roles</p>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <!-- Add User Button -->
            <div style="margin-bottom: 20px;">
                <button class="btn btn-primary" onclick="document.getElementById('addUserModal').style.display='block'">
                    <i class="fa fa-plus"></i> Add New User
                </button>
            </div>

            <!-- Users Table -->
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Full Name</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = mysqli_fetch_assoc($usersResult)): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><strong><?= htmlspecialchars($user['username']) ?></strong></td>
                            <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['full_name'] ?? 'N/A') ?></td>
                            <td>
                                <?php
                                $role = $user['role'] ?? ($user['username'] == 'admin' ? 'admin' : 'user');
                                $roleClass = $role == 'admin' ? 'status-completed' : 'status-pending';
                                ?>
                                <span class="status-badge <?= $roleClass ?>"><?= ucfirst($role) ?></span>
                            </td>
                            <td><?= isset($user['created_at']) ? date('M d, Y', strtotime($user['created_at'])) : 'N/A' ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick='editUser(<?= json_encode($user) ?>)'>
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="users.php?delete=1&id=<?= $user['id'] ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this user?')">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
        <div style="background:white; width:500px; margin:50px auto; padding:30px; border-radius:8px;">
            <h2 style="margin-bottom:20px;"><i class="fa fa-user-plus"></i> Add New User</h2>
            <form method="post" action="users.php">
                <div class="form-group">
                    <label>Username *</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="form-control">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div style="margin-top:20px;">
                    <button type="submit" name="add_user" class="btn btn-success">
                        <i class="fa fa-save"></i> Add User
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('addUserModal').style.display='none'">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
        <div style="background:white; width:500px; margin:50px auto; padding:30px; border-radius:8px;">
            <h2 style="margin-bottom:20px;"><i class="fa fa-edit"></i> Edit User</h2>
            <form method="post" action="users.php">
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="form-group">
                    <label>Username *</label>
                    <input type="text" name="username" id="edit_username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password (leave empty to keep current)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="edit_email" class="form-control">
                </div>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" id="edit_full_name" class="form-control">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" id="edit_role" class="form-control">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div style="margin-top:20px;">
                    <button type="submit" name="edit_user" class="btn btn-success">
                        <i class="fa fa-save"></i> Update User
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('editUserModal').style.display='none'">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editUser(user) {
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_email').value = user.email || '';
            document.getElementById('edit_full_name').value = user.full_name || '';
            document.getElementById('edit_role').value = user.role || (user.username === 'admin' ? 'admin' : 'user');
            document.getElementById('editUserModal').style.display = 'block';
        }
    </script>

    <?php include('footer.php'); ?>
</body>
</html>
