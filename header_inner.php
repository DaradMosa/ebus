<!-- Header Area Start - Inner Pages Version with Background -->
<header class="header-area bg-img" style="background-image: url(img/bg-img/14.jpg);">
    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 h-100">
                <div class="main-menu h-100">
                    <nav class="navbar h-100 navbar-expand-lg">
                        <!-- Logo Area  -->
                        <a class="navbar-brand" href="index.php"><img src="img/core-img/logo.png" alt="Logo"></a>

                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#studioMenu" aria-controls="studioMenu" aria-expanded="false" aria-label="Toggle navigation"><i class="fa fa-bars"></i> Menu</button>

                        <div class="collapse navbar-collapse" id="studioMenu">
                            <!-- Menu Area Start  -->
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item <?php echo isActive('index.php'); ?>">
                                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pages</a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="index.php">Home</a>
                                        <a class="dropdown-item" href="about.php">About</a>
                                        <a class="dropdown-item" href="portfolio.php">Portfolio</a>
                                        <a class="dropdown-item" href="blog.php">Blog</a>
                                        <a class="dropdown-item" href="contact.php">Contact</a>
                                        <a class="dropdown-item" href="elements.php">Elements</a>
                                    </div>
                                </li>
                                <li class="nav-item <?php echo isActive('about.php'); ?>">
                                    <a class="nav-link" href="about.php">About</a>
                                </li>
                                <li class="nav-item <?php echo isActive('portfolio.php'); ?>">
                                    <a class="nav-link" href="portfolio.php">Portfolio</a>
                                </li>
                                <li class="nav-item <?php echo isActive('blog.php'); ?>">
                                    <a class="nav-link" href="blog.php">Blog</a>
                                </li>
                                <li class="nav-item <?php echo isActive('contact.php'); ?>">
                                    <a class="nav-link" href="contact.php">contact</a>
                                </li>
                            </ul>
                            <!-- Search Form -->
                            <div class="header-search-form ml-auto">
                                <form action="#">
                                    <input type="search" class="form-control" placeholder="Input your keyword then press enter..." id="search" name="search">
                                    <input class="d-none" type="submit" value="submit">
                                </form>
                            </div>
                            <!-- Search btn -->
                            <div id="searchbtn">
                                <img src="img/core-img/search.png" alt="">
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Header Area End -->

