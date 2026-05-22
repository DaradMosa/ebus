<!DOCTYPE html>
<html lang="en">
<?php
include('config.php');
include('head.php');
?>
<body>
    <?php
    include('preloader.php');
    include('header_inner.php');
    include('social_sidebar_simple.php');
    ?>

    <!-- Project Area Start -->
    <div class="gallery_area clearfix">
        <div class="container-fluid clearfix">
            <div class="gallery_menu">
                <?php displayPortfolioFilters(); ?>
            </div>

            <div class="row portfolio-column">
                <?php displayPortfolioItems(); ?>
            </div>

            <div class="row">
                <div class="col-12 text-center mt-70">
                    <a href="#" class="btn studio-btn"><img src="img/core-img/logo-icon.png" alt=""> Load More</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Project Area End -->

    <?php
    include('footer_detailed.php');
    include('footer.php');
    ?>

    <?php include('scripts.php'); ?>

</body>

</html>

