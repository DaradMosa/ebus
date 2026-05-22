<!DOCTYPE html>
<html lang="en">
<?php
include_once('config.php');

// Require login to access cart
requireLogin();

include_once('head.php');
?>
<body>
    <?php
    include('preloader.php');
    include('header.php');
    include('social_sidebar.php');
    ?>

    <!-- Cart Area Start -->
    <section class="cart-area section_padding_100 mt-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="cart-content bg-white p-5 shadow">
                        <div class="section-heading text-center mb-50">
                            <h2 class="text-primary">Shopping Cart</h2>
                            <p>Review your items and proceed to PayPal checkout</p>
                        </div>

<?php
if(isset($_SESSION)){
    // Check if cart has items
    $hasItems = false;
    foreach($_SESSION as $k => $v){
        if(substr($k,0,5) == "prod_"){
            $hasItems = true;
            break;
        }
    }
    
    if($hasItems){
        // Generate order number and save order to database
        $orderNumber = 'ORD-' . date('YmdHis') . '-' . rand(1000, 9999);
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
        
        // Calculate grand total first
        $grandTotalCalc = 0;
        foreach($_SESSION as $k => $v){
            if(substr($k,0,5) == "prod_"){
                $iid = substr($k,5);
                $q = "select itemPrice from items where itemid = " . intval($iid);
                $results = mysqli_query($conn, $q);
                $row = mysqli_fetch_assoc($results);
                if($row){
                    $grandTotalCalc += $v * $row['itemPrice'];
                }
            }
        }
        
        // Insert order into database
        $insertOrder = "INSERT INTO orders (order_number, user_id, total_amount, payment_status, payment_method) 
                        VALUES ('$orderNumber', $userId, $grandTotalCalc, 'pending', 'PayPal')";
        mysqli_query($conn, $insertOrder);
        $orderId = mysqli_insert_id($conn);
        
        // Insert order items
        foreach($_SESSION as $k => $v){
            if(substr($k,0,5) == "prod_"){
                $iid = substr($k,5);
                $q = "select * from items where itemid = " . intval($iid);
                $results = mysqli_query($conn, $q);
                $row = mysqli_fetch_assoc($results);
                if($row){
                    $itemTotal = $v * $row['itemPrice'];
                    $itemName = mysqli_real_escape_string($conn, $row['itemName']);
                    $itemDesc = mysqli_real_escape_string($conn, $row['itemDescription']);
                    
                    $insertItem = "INSERT INTO order_items (order_id, item_id, item_name, item_description, item_price, quantity, item_total) 
                                   VALUES ($orderId, {$row['itemid']}, '$itemName', '$itemDesc', {$row['itemPrice']}, $v, $itemTotal)";
                    mysqli_query($conn, $insertItem);
                }
            }
        }
        
        echo "<div class='table-responsive'>";
        echo "<table class='table table-bordered'>";
        ?>
        <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_blank">
        <input type="hidden" name="cmd" value="_cart">
        <input type="hidden" name="business" value="sb-test@business.example.com">
        <input type="hidden" name="upload" value="1">
        <input type="hidden" name="currency_code" value="USD">
        <input type="hidden" name="charset" value="utf-8">
        <input type="hidden" name="rm" value="2">
        <input type="hidden" name="return" value="http://<?= $_SERVER['HTTP_HOST'] ?>/thank_you.php?order=<?= $orderNumber ?>">
        <input type="hidden" name="cancel_return" value="http://<?= $_SERVER['HTTP_HOST'] ?>/cart.php">

        <?php
        echo "<thead class='table-dark'>";
        echo "<tr><th>#</th><th>Product Name</th><th>Price</th><th>Quantity</th><th>Item Total</th></tr>";
        echo "</thead><tbody>";
        
        $counter = 0;
        $grandTotal = 0;
        foreach($_SESSION as $k => $v){
            
            if(substr($k,0,5) == "prod_"){
                //1. read item id
                $iid = substr($k,5);
                $counter++;
                //2. fetch item info from DB
                global $conn;
                $q = "select * from items where itemid = " . $iid ;
                $results = mysqli_query($conn, $q);
                $row = mysqli_fetch_assoc($results);
                extract($row);
                
                $itemTotal = $v * $itemPrice;
                $grandTotal += $itemTotal;
                //3. Display info in a table
                echo "<tr>";
                echo "<td>$counter</td>";
                echo "<td><strong>$itemName</strong><br><small class='text-muted'>" . substr($itemDescription, 0, 100) . "...</small></td>";
                echo "<td>$$itemPrice</td>";
                echo "<td>$v</td>";
                echo "<td><strong>$$itemTotal</strong></td>";
                echo "</tr>";
                
                echo "<input type=\"hidden\" name=\"item_name_$counter\" value=\"$itemName\">";
                echo "<input type=\"hidden\" name=\"item_number_$counter\" value=\"$counter\">";
                echo "<input type=\"hidden\" name=\"amount_$counter\" value=\"$itemPrice\">" ;
                echo "<input type=\"hidden\" name=\"quantity_$counter\" value=\"$v\">";
            }
        }
        echo "</tbody>";
        echo "<tfoot>";
        echo "<tr class='table-success'><td colspan='4'><strong>Grand Total</strong></td><td><strong>$$grandTotal</strong></td></tr>";
        echo "</tfoot>";
        echo "</table>";
        echo "</div>";
        
        ?>
        <div class="text-center mt-4">
            <input type="image" name="submit" 
                   src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" 
                   border="0" alt="PayPal - The safer, easier way to pay online"
                   class="paypal-button">
            <br><br>
            <a href="products.php" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left"></i> Continue Shopping
            </a>
        </div>
        </form>
        
        <?php
    } else {
        // Empty cart message
        echo "<div class='text-center py-5'>";
        echo "<i class='fa fa-shopping-cart text-muted' style='font-size: 80px;'></i>";
        echo "<h3 class='mt-4 text-muted'>Your cart is empty</h3>";
        echo "<p class='text-muted'>Add some products to your cart to get started.</p>";
        echo "<a href='products.php' class='btn studio-btn'>";
        echo "<img src='img/core-img/logo-icon.png' alt=''> Browse Products";
        echo "</a>";
        echo "</div>";
    }
} else {
    echo "<div class='text-center py-5'>";
    echo "<h3 class='text-muted'>Session not available</h3>";
    echo "</div>";
}
?>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Cart Area End -->

    <?php include('footer_detailed.php'); ?>
    <?php include('scripts.php'); ?>
</body>
</html>
