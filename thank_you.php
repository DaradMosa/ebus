<!DOCTYPE html>
<html lang="en">
<?php
include_once('config.php');
include_once('head.php');

// Handle PayPal return
if(isset($_GET['st'])){
    // Payment successful
    $transactionId = $_GET['tx'] ?? '';
    $amount = $_GET['amt'] ?? '';
    $currency = $_GET['cc'] ?? 'USD';
    
    // Save transaction to database
    if($transactionId){
        global $conn;
        extract($_GET);
        $items = "";
        foreach($_GET as $k => $v){
            if(substr($k,0,9) == "item_name")
                $items .= $v;
            if(substr($k,0,8) == "quantity")
                $items .= ":" . $v . ",";
        }
        
        $q = "INSERT INTO `transactions` (`transactionID`, `tCurrency`, `tAmount`, `tComplete`, `tItems`) 
              VALUES ('$tx', '$cc', '$amt', '$st', '$items')";
        mysqli_query($conn, $q);
    }
}
?>
<body>
    <?php
    include('preloader.php');
    include('header.php');
    include('social_sidebar.php');
    
    if(!isset($_GET['st'])){
        header("Location:index.php");
        exit;
    }
    ?>

    <!-- Thank You Area Start -->
    <section class="thank-you-area section_padding_100 mt-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">
                    <div class="thank-you-content bg-white p-5 shadow">
                        <center>
                            <h3 class="text-success mb-4">
                                <i class="fa fa-check-circle" style="font-size: 60px;"></i>
                            </h3>
                            <h2 class="text-primary">Your payment was successful! Thank you!</h2>
                            <p class="mb-4">Your transaction id is: <strong><?= htmlspecialchars($_GET['tx']) ?></strong></p>
                        </center>
                        <hr/>

                        <?php
                        if(isset($_SESSION)){
                            // Display items in the session
                            echo "<br/><center><table border='1' width='50%' class='table table-bordered'>";
                            echo "<tr><th>#</th><th>Name</th><th>Price</th><th>Quantity</th><th>Item Total</th></tr>";
                            
                            $counter = 0;
                            $grandTotal = 0;
                            foreach($_SESSION as $k => $v){
                                if(substr($k,0,5) == "prod_"){
                                    // Read item id
                                    $iid = substr($k,5);
                                    $counter++;
                                    
                                    // Fetch item info from DB
                                    global $conn;
                                    $q = "select * from items where itemid = " . intval($iid);
                                    $results = mysqli_query($conn, $q);
                                    $row = mysqli_fetch_assoc($results);
                                    
                                    if($row){
                                        extract($row);
                                        
                                        $itemTotal = $v * $itemPrice;
                                        $grandTotal += $itemTotal;
                                        
                                        // Display info in a table
                                        echo "<tr>";
                                        echo "<td>$counter</td>";
                                        echo "<td>$itemName</td>";
                                        echo "<td>\$$itemPrice</td>";
                                        echo "<td>$v</td>";
                                        echo "<td>\$$itemTotal</td>";
                                        echo "</tr>";
                                    }
                                }
                            }
                            
                            echo "<tr><td colspan='4'><strong>Grand Total</strong></td><td><strong>\$$grandTotal</strong></td></tr>";
                            echo "</table>";
                            
                            echo "<br/>";
                            echo "<button class='btn btn-primary' onclick='window.print()'><i class='fa fa-print'></i> Print Receipt</button> ";
                            echo "<a href='index.php?clearsession=1'><button class='btn btn-secondary'><i class='fa fa-home'></i> Back to Home</button></a>";
                            echo "</center>";
                            
                            // Clear cart after displaying
                            foreach($_SESSION as $k => $v){
                                if(substr($k, 0, 5) == "prod_"){
                                    unset($_SESSION[$k]);
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Thank You Area End -->

    <?php include('footer_detailed.php'); ?>
    <?php include('scripts.php'); ?>
</body>
</html>
