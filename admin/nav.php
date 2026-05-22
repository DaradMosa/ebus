<nav class="admin-nav">
    <h2><i class="fa fa-cogs"></i> Admin Panel</h2>
    <ul>
        <li><a href="/admin/index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
            <i class="fa fa-dashboard"></i> Dashboard
        </a></li>
        <li><a href="/admin/items.php" class="<?= basename($_SERVER['PHP_SELF']) == 'items.php' ? 'active' : '' ?>">
            <i class="fa fa-list"></i> Items
        </a></li>
        <li><a href="/admin/categories.php" class="<?= basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : '' ?>">
            <i class="fa fa-folder"></i> Categories
        </a></li>
        <li><a href="/admin/transactions.php" class="<?= basename($_SERVER['PHP_SELF']) == 'transactions.php' ? 'active' : '' ?>">
            <i class="fa fa-credit-card"></i> Transactions
        </a></li>
        <li><a href="/admin/users.php" class="<?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : '' ?>">
            <i class="fa fa-users"></i> Users
        </a></li>
        <li><a href="/admin/reporting.php" class="<?= basename($_SERVER['PHP_SELF']) == 'reporting.php' ? 'active' : '' ?>">
            <i class="fa fa-bar-chart"></i> Reports
        </a></li>
        <li><a href="/index.php" target="_blank">
            <i class="fa fa-external-link"></i> View Site
        </a></li>
    </ul>
    <div class="nav-right">
        <span style="margin-right: 20px;">
            <i class="fa fa-user"></i> <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>
        </span>
        <a href="/logout.php" style="color: #e74c3c;">
            <i class="fa fa-sign-out"></i> Logout
        </a>
    </div>
</nav>

