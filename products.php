<!DOCTYPE html>
<html lang="en">
<?php
include_once('config.php');
include_once('head.php');
?>
<body>
    <?php
    include('preloader.php');
    include('header.php');
    include('social_sidebar.php');
    ?>

    <!-- Products Area Start -->
    <section class="products-area section_padding_100 mt-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading text-center mb-100">
                        <h2>Our Services</h2>
                        <p>Choose from our range of professional digital services</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php
                global $conn;
                $q = "SELECT i.*, c.catName FROM items i JOIN categories c ON i.itemCategory = c.catid ORDER BY i.itemCategory, i.itemName";
                $results = mysqli_query($conn, $q);
                
                while($row = mysqli_fetch_assoc($results)){
                    extract($row);
                    $short = substr($itemDescription, 0, 150) . "...";
                ?>
                
                <div class="col-lg-6 col-md-12 mb-5">
                    <div class="single-service-area bg-white p-4 shadow h-100">
                        <div class="service-content">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-primary"><?= $catName ?></span>
                                <h4 class="text-success">$<?= $itemPrice ?></h4>
                            </div>
                            
                            <h4 class="mb-3">
                                <a href="item.php?itemid=<?= $itemid ?>" class="text-decoration-none">
                                    <?= $itemName ?>
                                </a>
                            </h4>
                            
                            <p class="text-muted mb-4"><?= $short ?></p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="item.php?itemid=<?= $itemid ?>" class="btn btn-outline-primary">
                                    <i class="fa fa-eye"></i> View Details
                                </a>
                                <?php if (isLoggedIn()): ?>
                                    <a href="addToCart.php?itemid=<?= $itemid ?>" class="btn studio-btn">
                                        <img src="img/core-img/logo-icon.png" alt=""> Add to Cart
                                    </a>
                                <?php else: ?>
                                    <a href="login.php?redirect=<?= urlencode('addToCart.php?itemid=' . $itemid) ?>" class="btn studio-btn">
                                        <img src="img/core-img/logo-icon.png" alt=""> Login to Buy
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php } ?>
            </div>
        </div>
    </section>
    <!-- Products Area End -->

    <?php include('footer_detailed.php'); ?>
    <?php include('scripts.php'); ?>
</body>
</html>
