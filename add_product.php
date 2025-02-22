<?php
include 'db_connect.php'; 
include 'header.php'; 
$conn = getDatabaseConnection();

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $name    = $conn->real_escape_string($_POST['product_name']);
    $cost    = $conn->real_escape_string($_POST['cost_price']);
    $selling = $conn->real_escape_string($_POST['selling_price']);
    $stock   = $conn->real_escape_string($_POST['stock_qty']);

    // Insert product into the database
    $sql = "INSERT INTO product (ProductName, CostPrice, SellingPrice, StockQty) 
            VALUES ('$name', '$cost', '$selling', '$stock')";

    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert alert-success'>New product added successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }

    $conn->close();
    // Optionally, you can redirect after adding the product:
    // header("Location: products.php");
    // exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Product</title>
    <!-- Bootstrap CSS should already be included in your header.php -->
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <!-- Display message if set -->
                <?php 
                if(!empty($message)) {
                    echo $message;
                }
                ?>
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="card-title mb-0">Add New Product</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="add_product.php">
                            <div class="mb-3">
                                <label class="form-label">Product Name:</label>
                                <input type="text" name="product_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cost Price:</label>
                                <input type="number" step="0.01" name="cost_price" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Selling Price:</label>
                                <input type="number" step="0.01" name="selling_price" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stock Quantity:</label>
                                <input type="number" name="stock_qty" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Add Product</button>
                            <a href="manage_products.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include 'footer.php'; ?>
<!-- Bootstrap JS (if not already included in your footer or header) -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>
