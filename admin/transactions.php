<!DOCTYPE html>
<html lang="en">
<?php
include('../config.php');
requireAdmin();
include('head.php');
?>
<body>
    <?php include('nav.php'); ?>
    
    <div class="admin-container">
        <h1 class="mt-4"><i class="fa fa-credit-card"></i> Transactions</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Transactions</li>
        </ol>
        
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa fa-table me-1"></i>
                All Transactions
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="admin-table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Completion</th>
                            <th>Date</th>
                            <th>Items</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Completion</th>
                            <th>Date</th>
                            <th>Items</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        global $conn;
                        
                        // Check if transactions table exists
                        $checkTable = mysqli_query($conn, "SHOW TABLES LIKE 'transactions'");
                        
                        if(mysqli_num_rows($checkTable) > 0){
                            $q = "SELECT * FROM transactions ORDER BY id DESC";
                            $results = mysqli_query($conn, $q);
                            
                            while($row = mysqli_fetch_array($results)){
                                extract($row);
                                
                                // Parse items
                                $i = explode("," , $tItems);
                                $ii = "";
                                foreach($i as $k => $v){
                                    if($v == "") continue;
                                    $v = explode(":",$v);
                                    if(isset($v[0]) && isset($v[1])){
                                        $ii .= $v[0] . " Qty: " . $v[1] . "<br/>";
                                    }
                                }
                                
                                echo "<tr>";
                                echo "<td>$transactionID</td>";
                                echo "<td>$tCurrency</td>";
                                echo "<td>\$$tAmount</td>";
                                echo "<td><span class='status-badge " . ($tComplete == 'Completed' ? 'status-completed' : 'status-pending') . "'>$tComplete</span></td>";
                                echo "<td>" . (isset($created_at) ? date('M d, Y h:i A', strtotime($created_at)) : 'N/A') . "</td>";
                                echo "<td>$ii</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No transactions table found. Please run database migration.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <?php include('footer.php'); ?>
</body>
</html>
