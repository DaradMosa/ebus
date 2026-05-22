<!DOCTYPE html>
<html lang="en">
<?php
include_once('config.php');
include('head.php');

// Get item details
if(isset($_GET['itemid'])){
    global $conn;
    $q = "select * from items where itemid = " . $_GET['itemid'] ;
    $results = mysqli_query($conn, $q);
    $row = mysqli_fetch_assoc($results);
    extract($row);
}
?>
<body>
    <?php
    include('preloader.php');
    include('header.php');
    include('social_sidebar.php');
    ?>

    <!-- Product Detail Area Start -->
    <section class="product-detail-area section_padding_100 mt-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-5">
                    <div class="product-image">
                        <img class="img-fluid rounded shadow" src="https://placehold.co/500x400/007bff/ffffff?text=<?= urlencode($itemName) ?>" alt="<?= $itemName ?>">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product-details">
                        <div class="breadcrumb mb-4">
                            <a href="products.php" class="text-muted">Products</a> 
                            <span class="text-muted"> / </span>
                            <span class="text-primary"><?= $itemName ?></span>
                        </div>
                        
                        <h1 class="mb-4"><?= $itemName ?></h1>
                        
                        <div class="price mb-4">
                            <h2 class="text-success">$<?= $itemPrice ?></h2>
                        </div>
                        
                        <div class="description mb-4">
                            <h5>Description</h5>
                            <p class="text-muted"><?= $itemDescription ?></p>
                        </div>
                        
                        <div class="product-actions">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <?php if (isLoggedIn()): ?>
                                        <a href="addToCart.php?itemid=<?= $_GET['itemid'] ?>" class="btn studio-btn w-100">
                                            <img src="img/core-img/logo-icon.png" alt=""> Add to Cart
                                        </a>
                                    <?php else: ?>
                                        <a href="login.php?redirect=<?= urlencode('addToCart.php?itemid=' . $_GET['itemid']) ?>" class="btn studio-btn w-100">
                                            <img src="img/core-img/logo-icon.png" alt=""> Login to Buy
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <a href="products.php" class="btn btn-outline-secondary w-100">
                                        <i class="fa fa-arrow-left"></i> Back to Products
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="product-features mt-5">
                            <h5>What's Included:</h5>
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check text-success"></i> Professional service delivery</li>
                                <li><i class="fa fa-check text-success"></i> Quality guarantee</li>
                                <li><i class="fa fa-check text-success"></i> Customer support</li>
                                <li><i class="fa fa-check text-success"></i> Timely completion</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Detail Area End -->

    <?php include('footer_detailed.php'); ?>
    <?php include('scripts.php'); ?>
</body>
</html>
