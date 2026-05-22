<!DOCTYPE html>
<html lang="en">
<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (loginUser($username, $password)) {
        $redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? 'index.php';
        header('Location: ' . $redirect);
        exit;
    } else {
        $error_message = "Invalid username or password";
    }
}

// Get redirect URL for form
$redirect_url = $_GET['redirect'] ?? 'index.php';

include('head.php');
?>
<body>
    <?php
    include('preloader.php');
    include('header.php');
    include('social_sidebar.php');
    ?>

    <!-- Login Area Start -->
    <section class="login-area section_padding_100 mt-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="login-form-area bg-white p-5 shadow">
                        <div class="section-heading text-center mb-50">
                            <h2 class="text-primary">Login</h2>
                            <p>Please enter your credentials to access your account</p>
                        </div>

                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger text-center mb-4">
                                <?= htmlspecialchars($error_message) ?>
                            </div>
                        <?php endif; ?>

                        <form action="login.php" method="POST">
                            <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect_url) ?>">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username" id="username" 
                                           placeholder="Enter your username" required 
                                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                                </div>
                                <div class="col-12 mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="password" 
                                           placeholder="Enter your password" required>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn studio-btn">
                                        <img src="img/core-img/logo-icon.png" alt=""> Login
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Login Area End -->

    <?php include('footer_detailed.php'); ?>
    <?php include('scripts.php'); ?>
</body>
</html>