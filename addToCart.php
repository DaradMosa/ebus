<?php
include_once('config.php');

// Require login to add items to cart
requireLogin();

$itemid = $_GET['itemid'];
if(isset($_SESSION["prod_$itemid"])){
	$_SESSION["prod_$itemid"]++;
}else		
	$_SESSION["prod_$itemid"] = 1;

header("Location:cart.php");
?>
