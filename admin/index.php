<!DOCTYPE html>
<html lang="en">
<?php
include('../config.php');
requireAdmin();

// Get dashboard statistics
$stats = [];

// Total users
$userQuery = "SELECT COUNT(*) as total FROM users";
$userResult = mysqli_query($conn, $userQuery);
$stats['total_users'] = mysqli_fetch_assoc($userResult)['total'];

// Total orders
$orderQuery = "SELECT COUNT(*) as total FROM orders";
$orderResult = mysqli_query($conn, $orderQuery);
$stats['total_orders'] = mysqli_fetch_assoc($orderResult)['total'];

// Total revenue
$revenueQuery = "SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'completed'";
$revenueResult = mysqli_query($conn, $revenueQuery);
$stats['total_revenue'] = mysqli_fetch_assoc($revenueResult)['total'] ?? 0;

// Pending orders
$pendingQuery = "SELECT COUNT(*) as total FROM orders WHERE payment_status = 'pending'";
$pendingResult = mysqli_query($conn, $pendingQuery);
$stats['pending_orders'] = mysqli_fetch_assoc($pendingResult)['total'];

// Recent orders (last 10)
$recentQuery = "SELECT o.*, u.username 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.order_date DESC LIMIT 10";
$recentOrders = mysqli_query($conn, $recentQuery);

// Today's stats
$todayQuery = "SELECT COUNT(*) as count, SUM(total_amount) as revenue 
               FROM orders 
               WHERE DATE(order_date) = CURDATE()";
$todayResult = mysqli_query($conn, $todayQuery);
$todayStats = mysqli_fetch_assoc($todayResult);

include('head.php');
?>
<body>
    <?php include('nav.php'); ?>
    
    <div class="admin-container">
        <div class="admin-content">
            <div class="page-header">
                <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
                <p>Welcome back, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>! Here's what's happening today.</p>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="stat-details">
                        <h3><?= $stats['total_users'] ?></h3>
                        <p>Total Users</p>
                    </div>
                </div>

                <div class="stat-card stat-success">
                    <div class="stat-icon">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="stat-details">
                        <h3><?= $stats['total_orders'] ?></h3>
                        <p>Total Orders</p>
                    </div>
                </div>

                <div class="stat-card stat-info">
                    <div class="stat-icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <div class="stat-details">
                        <h3>$<?= number_format($stats['total_revenue'], 2) ?></h3>
                        <p>Total Revenue</p>
                    </div>
                </div>

                <div class="stat-card stat-warning">
                    <div class="stat-icon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <div class="stat-details">
                        <h3><?= $stats['pending_orders'] ?></h3>
                        <p>Pending Orders</p>
                    </div>
                </div>
            </div>

            <!-- Today's Stats -->
            <div class="today-stats">
                <h3><i class="fa fa-calendar"></i> Today's Performance</h3>
                <div class="today-grid">
                    <div class="today-item">
                        <strong><?= $todayStats['count'] ?></strong>
                        <span>Orders Today</span>
                    </div>
                    <div class="today-item">
                        <strong>$<?= number_format($todayStats['revenue'] ?? 0, 2) ?></strong>
                        <span>Revenue Today</span>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="recent-orders">
                <h3><i class="fa fa-list"></i> Recent Orders</h3>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order Number</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $count = 0;
                            while($order = mysqli_fetch_assoc($recentOrders)): 
                                $count++;
                                $statusClass = $order['payment_status'] == 'completed' ? 'status-completed' : 'status-pending';
                            ?>
                            <tr>
                                <td><?= $count ?></td>
                                <td><strong><?= htmlspecialchars($order['order_number']) ?></strong></td>
                                <td><?= htmlspecialchars($order['username'] ?? 'Guest') ?></td>
                                <td>$<?= number_format($order['total_amount'], 2) ?></td>
                                <td><span class="status-badge <?= $statusClass ?>"><?= ucfirst($order['payment_status']) ?></span></td>
                                <td><?= date('M d, Y h:i A', strtotime($order['order_date'])) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Charts -->
            <div class="charts-section">
                <div class="chart-row">
                    <div class="chart-card">
                        <h3><i class="fa fa-area-chart"></i> Monthly Revenue</h3>
                        <canvas id="revenueChart" width="100%" height="40"></canvas>
                    </div>
                    <div class="chart-card">
                        <h3><i class="fa fa-bar-chart"></i> Orders by Month</h3>
                        <canvas id="ordersChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3><i class="fa fa-bolt"></i> Quick Actions</h3>
                <div class="action-buttons">
                    <a href="/admin/items.php" class="action-btn btn-primary">
                        <i class="fa fa-list"></i> Manage Items
                    </a>
                    <a href="/admin/categories.php" class="action-btn btn-warning">
                        <i class="fa fa-folder"></i> Manage Categories
                    </a>
                    <a href="/admin/transactions.php" class="action-btn btn-success">
                        <i class="fa fa-credit-card"></i> View Transactions
                    </a>
                    <a href="/products.php" class="action-btn btn-info">
                        <i class="fa fa-eye"></i> View Store
                    </a>
                </div>
            </div>

        </div>
    </div>

    <?php include('footer.php'); ?>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script>
    // Revenue Chart
    var revenueCtx = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Revenue ($)',
                data: [
                    <?php
                    // Get monthly revenue for current year
                    for($m = 1; $m <= 12; $m++){
                        $monthQuery = "SELECT COALESCE(SUM(total_amount), 0) as total 
                                      FROM orders 
                                      WHERE YEAR(order_date) = YEAR(CURDATE()) 
                                      AND MONTH(order_date) = $m 
                                      AND payment_status = 'completed'";
                        $monthResult = mysqli_query($conn, $monthQuery);
                        $monthData = mysqli_fetch_assoc($monthResult);
                        echo $monthData['total'];
                        if($m < 12) echo ', ';
                    }
                    ?>
                ],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }]
            }
        }
    });

    // Orders Chart
    var ordersCtx = document.getElementById('ordersChart').getContext('2d');
    var ordersChart = new Chart(ordersCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Orders',
                data: [
                    <?php
                    // Get monthly orders for current year
                    for($m = 1; $m <= 12; $m++){
                        $monthQuery = "SELECT COUNT(*) as total 
                                      FROM orders 
                                      WHERE YEAR(order_date) = YEAR(CURDATE()) 
                                      AND MONTH(order_date) = $m";
                        $monthResult = mysqli_query($conn, $monthQuery);
                        $monthData = mysqli_fetch_assoc($monthResult);
                        echo $monthData['total'];
                        if($m < 12) echo ', ';
                    }
                    ?>
                ],
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });
    </script>
</body>
</html>
