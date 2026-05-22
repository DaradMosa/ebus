<!DOCTYPE html>
<html lang="en">
<?php
include('config.php');
include('head.php');
?>
<body>
    <?php
    include('preloader.php');
    include('header.php');
    include('social_sidebar.php');
    
    // Check for logout message
    if (isset($_GET['logged_out'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                <strong>Success!</strong> You have been logged out successfully.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>';
    }
    ?>
    
    <!-- ***** Welcome Area Start ***** -->
    <section class="welcome-area">
        <div class="carousel h-100 slide" data-ride="carousel" id="welcomeSlider">
            <div class="carousel-inner h-100">
                <?php displayCarouselSlides(); ?>
            </div>
            <ol class="carousel-indicators">
                <?php displayCarouselIndicators(); ?>
            </ol>
        </div>
    </section>
    <!-- ***** Welcome Area End ***** -->

    <!-- ***** Featured Portfolio Section Start ***** -->
    <section class="featured-portfolio-area section_padding_100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading text-center">
                        <span></span>
                        <h2>Our Recent Work</h2>
                        <p>Check out our latest projects from the database</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php displayFeaturedPortfolio(); ?>
            </div>
            <div class="row">
                <div class="col-12 text-center mt-4">
                    <a href="portfolio.php" class="btn studio-btn"><img src="img/core-img/logo-icon.png" alt=""> View All Projects</a>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Featured Portfolio Section End ***** -->

    <?php
    include('contact_modal.php');
    include('footer.php');
    ?>

    <?php include('scripts.php'); ?>

</body>

</html>

