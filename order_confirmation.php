<?php
session_start();
include 'db_connect.php';

// Ensure the customer is logged in
if (!isset($_SESSION['customer_id'])) {
    echo "<script>alert('You are not logged in. Please log in to confirm your order.'); window.location.href = 'login.php';</script>";
    exit;
}

$customer_id = $_SESSION['customer_id'];
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : null;

if (!$order_id) {
    echo "No Order ID provided.";
    exit;
}

$conn = getDatabaseConnection();

// Fetch order details by joining Orders, OrderItem, and Product tables.
// Note: OrderItem does not have a CustomerID column, so we join via Orders to verify the order belongs to the customer.
$sql = "SELECT p.ProductName, o.OrderDate 
        FROM Orders o
        JOIN OrderItem oi ON o.OrderID = oi.OrderID
        JOIN Product p ON oi.ProductID = p.ProductID
        WHERE o.OrderID = ? AND o.CustomerID = ?
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $order_id, $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $product_name = $row['ProductName'];
    $order_date   = $row['OrderDate'];
    $message = "Congratulations! Your order has been placed successfully.<br>
                Order ID: <strong>$order_id</strong><br>
                Order Date: <strong>$order_date</strong><br>
                Product: <strong>$product_name</strong>";
} else {
    $message = "Order details not found or you are not authorized to view this order.";
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <div class="card shadow-sm p-4">
        <h2 class="text-center">Order Confirmation</h2>
        <p class="text-center"><?php echo $message; ?></p>
        <a href="shop_now.php" class="btn btn-primary w-100">Continue Shopping</a>
    </div>
</div>

<div class="container my-5">
    <div class="card shadow-sm p-4">
        <a href="feedback.php?order_id=<?php echo $order_id; ?>&customer_id=<?php echo $customer_id; ?>" class="btn btn-success w-100">Leave Feedback</a>
    </div>
</div>

<!-- Optional: Pop-up -->
<script>
    window.onload = function() {
        alert("Congratulations! Your order has been placed successfully.");
    };
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>