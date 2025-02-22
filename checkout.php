<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

session_start();
include 'db_connect.php'; // Make sure this file returns a valid $conn
include 'header.php';

$conn = getDatabaseConnection();

// Debugging output (remove in production)
echo "Checkout page loaded.<br>";
echo "Customer ID: " . (isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : "Not set") . "<br>";
echo "Product ID from URL: " . (isset($_GET['product_id']) ? $_GET['product_id'] : "Not set") . "<br>";

// 1. Ensure the customer is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php?error=Please+log+in+to+checkout.");
    exit;
}

$customer_id = $_SESSION['customer_id'];

// 2. Retrieve product_id from the URL
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : null;
if (!$product_id) {
    die("Product details are missing. Please ensure you selected a product and try again.");
}

// 3. Fetch product details
$stmt = $conn->prepare("SELECT ProductName, SellingPrice, StockQty FROM Product WHERE ProductID = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("The selected product does not exist in the database.");
}
$product = $result->fetch_assoc();
$product_name  = $product['ProductName'];
$product_price = $product['SellingPrice'];
$stock_qty     = $product['StockQty'];
$stmt->close();

// 4. Check stock
if ($stock_qty <= 0) {
    die("Sorry, this product is out of stock.");
}

// 5. Handle the checkout form submission
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

    // Hard-coded for single product "Buy Now"
    $quantity  = 1;
    $tax_rate  = 0.07;
    $tax_amount = $product_price * $tax_rate;
    $total_amount = $product_price + $tax_amount;

    // (A) Insert into Orders
    $stmt_order = $conn->prepare("INSERT INTO Orders (CustomerID, OrderDate) VALUES (?, NOW())");
    if (!$stmt_order) {
        die("Prepare failed for Orders: " . $conn->error);
    }
    $stmt_order->bind_param("i", $customer_id);
    if ($stmt_order->execute()) {
        $order_id = $stmt_order->insert_id;
        echo "Order inserted into Orders table. Order ID: $order_id<br>";
        $stmt_order->close();
    } else {
        die("Error inserting into Orders: " . $stmt_order->error);
    }

    // (B) Insert into OrderItem
    $stmt_item = $conn->prepare("INSERT INTO OrderItem (OrderID, ProductID, Quantity, PricePerUnit, TaxRate) VALUES (?, ?, ?, ?, ?)");
    $stmt_item->bind_param("iiidd", $order_id, $product_id, $quantity, $product_price, $tax_rate);
    if ($stmt_item->execute()) {
        echo "OrderItem record created.<br>";
    } else {
        die("Error inserting into OrderItem: " . $stmt_item->error);
    }
    $stmt_item->close();

    // (C) Insert into Payment
    $payment_date = date("Y-m-d");
    $stmt_payment = $conn->prepare("INSERT INTO Payment (OrderID, AmountPaid, PaymentDate, PaymentMethod) VALUES (?, ?, ?, ?)");
    $stmt_payment->bind_param("idss", $order_id, $total_amount, $payment_date, $payment_method);
    if ($stmt_payment->execute()) {
        echo "Payment record created.<br>";
    } else {
        die("Error inserting into Payment: " . $stmt_payment->error);
    }
    $stmt_payment->close();

    // (D) Insert into Shipping
    $stmt_shipping = $conn->prepare("INSERT INTO Shipping (OrderID, Address, City, State, ZipCode, ShippingDate, DeliveryDate, ShippingMethod) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_shipping->bind_param("isssssss", $order_id, $address, $city, $state, $zipcode, $shipping_date, $delivery_date, $shipping_method);
    if ($stmt_shipping->execute()) {
        echo "Shipping record created.<br>";
    } else {
        die("Error inserting into Shipping: " . $stmt_shipping->error);
    }
    $stmt_shipping->close();

    // (E) Insert into Invoice (optional)
    // For demonstration, we store the same tax_amount, total_amount, and invoice date as now.
    $invoice_date = date("Y-m-d");
    $stmt_invoice = $conn->prepare("INSERT INTO Invoice (OrderID, CustomerID, TaxAmount, TotalPrice, InvoiceDate) 
                                    VALUES (?, ?, ?, ?, ?)");
    $stmt_invoice->bind_param("iidds", $order_id, $customer_id, $tax_amount, $total_amount, $invoice_date);
    if ($stmt_invoice->execute()) {
        echo "Invoice record created.<br>";
    } else {
        die("Error inserting into Invoice: " . $stmt_invoice->error);
    }
    $stmt_invoice->close();

    // (F) Update Product stock
    $stmt_stock = $conn->prepare("UPDATE Product SET StockQty = StockQty - ? WHERE ProductID = ?");
    $stmt_stock->bind_param("ii", $quantity, $product_id);
    if ($stmt_stock->execute()) {
        echo "Product stock updated.<br>";
    } else {
        die("Error updating product stock: " . $stmt_stock->error);
    }
    $stmt_stock->close();

    // Finally, redirect to order confirmation
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
    <div class="card p-4">
        <h2 class="text-center">Checkout</h2>
        <!-- <p><strong>Product:</strong> 3?></p>
        <p><strong>Price:</strong> $</p> -->

        <form action="checkout.php?product_id=<?php echo $product_id; ?>" method="POST">
            <div class="mb-3">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select name="payment_method" id="payment_method" class="form-select" required>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Online Banking">Online Banking</option>
                </select>
            </div>

            <h4>Shipping Information</h4>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" id="address" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" name="city" id="city" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="state" class="form-label">State</label>
                <input type="text" name="state" id="state" class="form-control" required>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
