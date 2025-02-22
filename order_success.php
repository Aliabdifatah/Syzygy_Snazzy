<?php
session_start();
include 'header.php'; 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Success</title>
    <!-- Optional Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Thank You!</h2>
    <p>Your order has been placed successfully.</p>
    <a href="products.php" class="btn btn-primary">Continue Shopping</a>
</div>
</body>
</html>
<?php include 'footer.php'; ?>