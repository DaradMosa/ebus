<!DOCTYPE html>
<html lang="en">
<?php
include('../config.php');
requireAdmin();

// Get filter parameters
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
$status = isset($_GET['status']) ? $_GET['status'] : 'all';

// Build query
$whereClause = "WHERE DATE(order_date) BETWEEN '$startDate' AND '$endDate'";
if($status != 'all'){
    $whereClause .= " AND payment_status = '$status'";
}

// Get summary data
$summaryQuery = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(total_amount) as total_revenue,
                    AVG(total_amount) as avg_order_value
                 FROM orders $whereClause";
$summaryResult = mysqli_query($conn, $summaryQuery);
$summary = mysqli_fetch_assoc($summaryResult);

// Get orders
$ordersQuery = "SELECT o.*, u.username 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                $whereClause 
                ORDER BY o.order_date DESC";
$ordersResult = mysqli_query($conn, $ordersQuery);

include('head.php');
?>
<body>
    <?php include('nav.php'); ?>
    
    <div class="admin-container">
        <h1 class="mt-4"><i class="fa fa-bar-chart"></i> Reports</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Reports</li>
        </ol>
        
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa fa-filter"></i> Filter Reports
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="<?= $startDate ?>">
                    </div>
                    <div class="col-md-3">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" value="<?= $endDate ?>">
                    </div>
                    <div class="col-md-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="all" <?= $status == 'all' ? 'selected' : '' ?>>All</option>
                            <option value="completed" <?= $status == 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary form-control"><i class="fa fa-search"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Summary Cards -->
        <div class="stats-grid mb-4">
            <div class="stat-card stat-primary">
                <div class="stat-icon"><i class="fa fa-shopping-cart"></i></div>
                <div class="stat-details">
                    <h3><?= $summary['total_orders'] ?></h3>
                    <p>Total Orders</p>
                </div>
            </div>
            <div class="stat-card stat-success">
                <div class="stat-icon"><i class="fa fa-dollar"></i></div>
                <div class="stat-details">
                    <h3>$<?= number_format($summary['total_revenue'] ?? 0, 2) ?></h3>
                    <p>Total Revenue</p>
                </div>
            </div>
            <div class="stat-card stat-info">
                <div class="stat-icon"><i class="fa fa-calculator"></i></div>
                <div class="stat-details">
                    <h3>$<?= number_format($summary['avg_order_value'] ?? 0, 2) ?></h3>
                    <p>Avg Order Value</p>
                </div>
            </div>
        </div>
        
        <!-- Orders Table -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa fa-table"></i> Orders Report
            </div>
            <div class="card-body">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($order = mysqli_fetch_assoc($ordersResult)): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_number']) ?></td>
                            <td><?= htmlspecialchars($order['username'] ?? 'Guest') ?></td>
                            <td>$<?= number_format($order['total_amount'], 2) ?></td>
                            <td><span class="status-badge status-<?= $order['payment_status'] ?>"><?= ucfirst($order['payment_status']) ?></span></td>
                            <td><?= date('M d, Y h:i A', strtotime($order['order_date'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <?php include('footer.php'); ?>
</body>
</html>

