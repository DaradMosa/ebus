<!DOCTYPE html>
<html lang="en">
<?php
include('config.php');

// Process contact form
$form_result = processContactForm();

include('head.php');
?>
<body>
    <?php
    include('preloader.php');
    include('header_inner.php');
    include('social_sidebar_simple.php');
    ?>

    <section class="contact-area section_padding_100 mt-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-10">
                    <div class="contact-heading-text text-center mb-100">
                        <span></span>
                        <h2>Please get in touch</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vel lectus eu felis semper finibus ac eget ipsum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam vulputate id justo quis facilisis. Vestibulum id orci ligula. Sed tempor, nunc ut sodales pulvinar, mauris ante euismod magna, at elementum lectus leo sed enim. Praesent dictum suscipit tincidunt. Nulla facilisi. Aenean in mollis orci. Ut interdum vulputate ante a egestas. Pellentesque varius purus malesuada arcu semper vehicula. </p>
                    </div>
                </div>
                <!-- Contact Form Area -->
                <div class="col-10">
                    <?php if ($form_result !== null): ?>
                        <div class="alert <?php echo $form_result['success'] ? 'alert-success' : 'alert-danger'; ?> mb-4">
                            <?php echo $form_result['message']; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="contact-form-area">
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Your Name" 
                                           value="<?php echo ($form_result && !$form_result['success']) ? htmlspecialchars($_POST['name'] ?? '') : ''; ?>" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" 
                                           value="<?php echo ($form_result && !$form_result['success']) ? htmlspecialchars($_POST['email'] ?? '') : ''; ?>" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" 
                                           value="<?php echo ($form_result && !$form_result['success']) ? htmlspecialchars($_POST['subject'] ?? '') : ''; ?>" required>
                                </div>
                                <div class="col-12">
                                    <textarea name="message" class="form-control" id="message" cols="30" rows="10" placeholder="Message" required><?php echo ($form_result && !$form_result['success']) ? htmlspecialchars($_POST['message'] ?? '') : ''; ?></textarea>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn studio-btn mt-3" name="submit"><img src="img/core-img/logo-icon.png" alt=""> Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Google Maps -->
    <div class="map-area">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-10">
                    <div id="googleMap"></div>
                    <!-- Contact Info -->
                    <div class="contact-core-info d-flex align-items-center wow fadeInLeftBig" data-wow-delay="1s" data-wow-duration="1000ms">
                        <div class="contactInfo">
                            <img src="img/core-img/logo.png" alt="">
                            <!-- Single Footer Content -->
                            <div class="single-footer-content">
                                <img src="img/core-img/map.png" alt="">
                                <a href="#">Blvd Libertad, 34 m05200 Arévalo</a>
                            </div>
                            <!-- Single Footer Content -->
                            <div class="single-footer-content">
                                <img src="img/core-img/smartphone.png" alt="">
                                <a href="#">0034 37483 2445 322</a>
                            </div>
                            <!-- Single Footer Content -->
                            <div class="single-footer-content">
                                <img src="img/core-img/envelope-2.png" alt="">
                                <a href="#">hello@company.com</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Follow Me Instagram Area Start -->
    <section class="follow-me-instagram-area">
        <div class="container">
            <div class="row">
                <div class="col-11 ml-auto">
                    <div class="follow-me-title">
                        <h5>Follow me @ Instagram</h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- Instagram Feeds Area -->
        <div class="instagram-feeds-area owl-carousel">
            <div class="single-instagram-feeds">
                <img src="img/bg-img/i1.jpg" alt="">
                <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
            </div>
            <div class="single-instagram-feeds">
                <img src="img/bg-img/i2.jpg" alt="">
                <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
            </div>
            <div class="single-instagram-feeds">
                <img src="img/bg-img/i3.jpg" alt="">
                <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
            </div>
            <div class="single-instagram-feeds">
                <img src="img/bg-img/i4.jpg" alt="">
                <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
            </div>
            <div class="single-instagram-feeds">
                <img src="img/bg-img/i5.jpg" alt="">
                <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
            </div>
            <div class="single-instagram-feeds">
                <img src="img/bg-img/i6.jpg" alt="">
                <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
            </div>
            <div class="single-instagram-feeds">
                <img src="img/bg-img/i7.jpg" alt="">
                <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
            </div>
        </div>
    </section>
    <!-- Follow Me Instagram Area End -->

    <?php
    include('footer_detailed.php');
    include('footer.php');
    ?>

    <?php include('scripts.php'); ?>
    <!-- Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAwuyLRa1uKNtbgx6xAJVmWy-zADgegA2s"></script>
    <script src="js/map-active.js"></script>

</body>

</html>

