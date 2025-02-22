<?php
session_start();
include 'db_connect.php';
include 'header.php'; 
$conn = getDatabaseConnection();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Remove item from cart if requested
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $productID = intval($_GET['remove']);
    unset($_SESSION['cart'][$productID]);
    header("Location: cart.php");
    exit();
}

// Clear the entire cart if requested
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}

$totalPrice = 0;
$cartItems = [];

// If cart is not empty, retrieve product details
if (!empty($_SESSION['cart'])) {
    // Get all product IDs from the cart
    $ids = implode(',', array_keys($_SESSION['cart']));
    $sql = "SELECT * FROM Product WHERE ProductID IN ($ids)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $productID = $row['ProductID'];
        $quantity = $_SESSION['cart'][$productID];
        $subtotal = $row['SellingPrice'] * $quantity;
        $totalPrice += $subtotal;
        $cartItems[] = [
            'ProductID'   => $productID,
            'ProductName' => $row['ProductName'],
            'SellingPrice'=> $row['SellingPrice'],
            'Quantity'    => $quantity,
            'Subtotal'    => $subtotal
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shopping Cart</title>
    <!-- Optional Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Your Shopping Cart</h2>
    <?php if (!empty($cartItems)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price Per Unit</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['ProductName']) ?></td>
                    <td>$<?= number_format($item['SellingPrice'], 2) ?></td>
                    <td><?= $item['Quantity'] ?></td>
                    <td>$<?= number_format($item['Subtotal'], 2) ?></td>
                    <td>
                        <a href="cart.php?remove=<?= $item['ProductID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td colspan="2"><strong>$<?= number_format($totalPrice, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>
        <a href="cart.php?clear=true" class="btn btn-warning">Clear Cart</a>
        <a href="checkout.php" class="btn btn-success">Checkout</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>
</body>
</html>
<?php $conn->close(); ?>
<?php include 'footer.php'; ?>

