<!-- Admin Header Start -->
<header class="admin-header bg-primary text-white shadow-sm">
    <div class="container-fluid">
        <div class="row align-items-center py-3">
            <div class="col-md-6">
                <h4 class="mb-0">
                    <i class="fa fa-dashboard"></i> Admin Panel
                </h4>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="me-3">
                    <i class="fa fa-user"></i> <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>
                </span>
                <a href="../index.php" class="text-white me-3" title="View Site">
                    <i class="fa fa-home"></i> View Site
                </a>
                <a href="../logout.php" class="text-white" title="Logout">
                    <i class="fa fa-sign-out"></i> Logout
                </a>
            </div>
        </div>
    </div>
    
    <!-- Admin Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>" href="index.php">
                            <i class="fa fa-dashboard"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'orders.php') ? 'active' : '' ?>" href="orders.php">
                            <i class="fa fa-shopping-cart"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'users.php') ? 'active' : '' ?>" href="users.php">
                            <i class="fa fa-users"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'products.php') ? 'active' : '' ?>" href="products.php">
                            <i class="fa fa-box"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'messages.php') ? 'active' : '' ?>" href="messages.php">
                            <i class="fa fa-envelope"></i> Messages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'transactions.php') ? 'active' : '' ?>" href="transactions.php">
                            <i class="fa fa-credit-card"></i> Transactions
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<!-- Admin Header End -->
