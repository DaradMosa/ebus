<?php
include('config.php');

// Logout the user
logoutUser();

// Redirect to home page with logout message
header('Location: index.php?logged_out=1');
exit;
?>
