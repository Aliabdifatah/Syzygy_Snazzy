<?php
session_start();
include 'db_connect.php';
include 'header.php';

$conn = getDatabaseConnection();

// 1. Ensure the customer is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php?error=Please+log+in+to+checkout.");
    exit;
}

$customer_id = $_SESSION['customer_id'];

// 2. Check that the cart exists and is not empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Your cart is empty.");
}

// 3. Retrieve all products in the cart
$product_ids = array_keys($_SESSION['cart']);
$ids = implode(',', $product_ids);

// Get details of all products in the cart
$sql = "SELECT ProductID, ProductName, SellingPrice, StockQty FROM Product WHERE ProductID IN ($ids)";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    die("No products found for the items in your cart.");
}

$cartItems = [];
$totalOrderPrice = 0;
$totalTax = 0;
$tax_rate = 0.07;

while ($row = $result->fetch_assoc()) {
    $pid = $row['ProductID'];
    $quantity = $_SESSION['cart'][$pid];
    $price = $row['SellingPrice'];
    $stock_qty = $row['StockQty'];

    // Check stock for each product
    if ($quantity > $stock_qty) {
        die("Insufficient stock for product " . htmlspecialchars($row['ProductName']));
    }

    $subtotal = $price * $quantity;
    $tax_amount = $subtotal * $tax_rate;
    $total = $subtotal + $tax_amount;

    $cartItems[] = [
        'ProductID'   => $pid,
        'ProductName' => $row['ProductName'],
        'SellingPrice'=> $price,
        'Quantity'    => $quantity,
        'Subtotal'    => $subtotal,
        'TaxAmount'   => $tax_amount,
        'Total'       => $total
    ];

    $totalOrderPrice += $subtotal;
    $totalTax += $tax_amount;
}

$totalAmount = $totalOrderPrice + $totalTax;

// 4. Handle the checkout form submission
if (isset($_POST['checkout'])) {
    $payment_method  = $_POST['payment_method'];
    $address         = $_POST['address'];
    $city            = $_POST['city'];
    $state           = $_POST['state'];
    $zipcode         = $_POST['zipcode'];
    $shipping_method = $_POST['shipping_method'];

    // Example shipping/delivery dates
    $shipping_date   = date("Y-m-d");
    $delivery_date   = date("Y-m-d", strtotime('+7 days'));

    // (A) Insert into Orders (one order for all items)
    $stmt_order = $conn->prepare("INSERT INTO Orders (CustomerID, OrderDate) VALUES (?, NOW())");
    if (!$stmt_order) {
        die("Prepare failed for Orders: " . $conn->error);
    }
    $stmt_order->bind_param("i", $customer_id);
    if ($stmt_order->execute()) {
        $order_id = $stmt_order->insert_id;
        $stmt_order->close();
    } else {
        die("Error inserting into Orders: " . $stmt_order->error);
    }

    // (B) Insert each product into OrderItem
    foreach ($cartItems as $item) {
        $stmt_item = $conn->prepare("INSERT INTO OrderItem (OrderID, ProductID, Quantity, PricePerUnit, TaxRate) VALUES (?, ?, ?, ?, ?)");
        $stmt_item->bind_param("iiidd", $order_id, $item['ProductID'], $item['Quantity'], $item['SellingPrice'], $tax_rate);
        if (!$stmt_item->execute()) {
            die("Error inserting into OrderItem: " . $stmt_item->error);
        }
        $stmt_item->close();
    }

    // (C) Insert into Payment
    $payment_date = date("Y-m-d");
    $stmt_payment = $conn->prepare("INSERT INTO Payment (OrderID, AmountPaid, PaymentDate, PaymentMethod) VALUES (?, ?, ?, ?)");
    $stmt_payment->bind_param("idss", $order_id, $totalAmount, $payment_date, $payment_method);
    if (!$stmt_payment->execute()) {
        die("Error inserting into Payment: " . $stmt_payment->error);
    }
    $stmt_payment->close();

    // (D) Insert into Shipping
    $stmt_shipping = $conn->prepare("INSERT INTO Shipping (OrderID, Address, City, State, ZipCode, ShippingDate, DeliveryDate, ShippingMethod) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_shipping->bind_param("isssssss", $order_id, $address, $city, $state, $zipcode, $shipping_date, $delivery_date, $shipping_method);
    if (!$stmt_shipping->execute()) {
        die("Error inserting into Shipping: " . $stmt_shipping->error);
    }
    $stmt_shipping->close();

    // (E) Insert into Invoice
    $invoice_date = date("Y-m-d");
    $stmt_invoice = $conn->prepare("INSERT INTO Invoice (OrderID, CustomerID, TaxAmount, TotalPrice, InvoiceDate) VALUES (?, ?, ?, ?, ?)");
    $stmt_invoice->bind_param("iidds", $order_id, $customer_id, $totalTax, $totalAmount, $invoice_date);
    if (!$stmt_invoice->execute()) {
        die("Error inserting into Invoice: " . $stmt_invoice->error);
    }
    $stmt_invoice->close();

    // (F) Update Product Stock for each item
    foreach ($cartItems as $item) {
        $stmt_stock = $conn->prepare("UPDATE Product SET StockQty = StockQty - ? WHERE ProductID = ?");
        $stmt_stock->bind_param("ii", $item['Quantity'], $item['ProductID']);
        if (!$stmt_stock->execute()) {
            die("Error updating product stock: " . $stmt_stock->error);
        }
        $stmt_stock->close();
    }

    // Clear the cart after successful checkout
    unset($_SESSION['cart']);

    header("Location: order_confirmation.php?order_id=$order_id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout - Payment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
<div class="container my-5">
  <div class="card p-4 shadow">
    <h2 class="text-center mb-4">Checkout</h2>
    
    <!-- Order Summary Section -->
    <div class="mb-4">
      <h4 class="mb-3">Order Summary</h4>
      <ul class="list-group mb-3">
        <?php foreach ($cartItems as $item): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= htmlspecialchars($item['ProductName']) ?>
            <span>
              <?= $item['Quantity'] ?> x $<?= number_format($item['SellingPrice'], 2) ?> = $<?= number_format($item['Subtotal'], 2) ?>
            </span>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="mb-2">
        <strong>Total Price:</strong> $<?= number_format($totalOrderPrice, 2) ?>
      </div>
      <div class="mb-2">
        <strong>Total Tax:</strong> $<?= number_format($totalTax, 2) ?>
      </div>
      <div class="mb-2">
        <strong>Grand Total:</strong> $<?= number_format($totalAmount, 2) ?>
      </div>
    </div>

    <!-- Checkout Form -->
    <form action="checkout.php" method="POST">
      <div class="mb-3">
        <label for="payment_method" class="form-label">Payment Method</label>
        <select name="payment_method" id="payment_method" class="form-select" required>
          <option value="Cash on Delivery">Cash on Delivery</option>
          <option value="Credit Card">Credit Card</option>
          <option value="Online Banking">Online Banking</option>
        </select>
      </div>

      <h4 class="mt-4">Shipping Information</h4>
      <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" name="address" id="address" class="form-control" required>
      </div>
      <div class="row">
        <div class="mb-3 col-md-6">
          <label for="city" class="form-label">City</label>
          <input type="text" name="city" id="city" class="form-control" required>
        </div>
        <div class="mb-3 col-md-6">
          <label for="state" class="form-label">State</label>
          <input type="text" name="state" id="state" class="form-control" required>
        </div>
      </div>
      <div class="mb-3">
        <label for="zipcode" class="form-label">Zip Code</label>
        <input type="text" name="zipcode" id="zipcode" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="shipping_method" class="form-label">Shipping Method</label>
        <select name="shipping_method" id="shipping_method" class="form-select" required>
          <option value="Standard">Standard</option>
          <option value="Express">Express</option>
        </select>
      </div>

      <button type="submit" name="checkout" class="btn btn-primary w-100">Place Order</button>
    </form>
  </div>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
