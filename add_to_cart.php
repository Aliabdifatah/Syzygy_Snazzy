<?php
session_start(); // Ensure session starts correctly
include 'db_connect.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if product_id is set
if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    $productID = intval($_GET['product_id']);

    // If the product is already in the cart, increase the quantity
    if (isset($_SESSION['cart'][$productID])) {
        $_SESSION['cart'][$productID]++;
    } else {
        // If the product is not in the cart, add it with quantity 1
        $_SESSION['cart'][$productID] = 1; 
    }
} else {
    echo "Invalid Product ID.";
    exit();
}

// Redirect back to cart
header("Location: cart.php");
exit();
?>
