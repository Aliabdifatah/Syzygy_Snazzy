<?php
include 'db_connect.php';
include 'header.php'; 
$conn = getDatabaseConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch product details
    $sql = "SELECT * FROM product WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        echo "Product not found!";
        exit;
    }
} else {
    echo "No product ID provided.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['product_name'];
    $cost = $_POST['cost_price'];
    $selling = $_POST['selling_price'];
    $stock = $_POST['stock_qty'];

    // Update product details
    $update_sql = "UPDATE product SET 
                    ProductName = ?, 
                    CostPrice = ?, 
                    SellingPrice = ?, 
                    StockQty = ? 
                    WHERE ProductID = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sddii", $name, $cost, $selling, $stock, $id);

    if ($stmt->execute() === TRUE) {
        $stmt->close();
        $conn->close();
        // Redirect to manage products page after successful update
        header("Location: manage_products.php");
        exit;
    } else {
        echo "Error: " . $update_sql . "<br>" . $conn->error;
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Product</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2>Edit Product</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Product Name:</label>
                <input type="text" name="product_name" class="form-control" value="<?= htmlspecialchars($product['ProductName']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Cost Price:</label>
                <input type="number" step="0.01" name="cost_price" class="form-control" value="<?= htmlspecialchars($product['CostPrice']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Selling Price:</label>
                <input type="number" step="0.01" name="selling_price" class="form-control" value="<?= htmlspecialchars($product['SellingPrice']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock Quantity:</label>
                <input type="number" name="stock_qty" class="form-control" value="<?= htmlspecialchars($product['StockQty']); ?>" required>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="manage_products.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
<?php include 'footer.php'; ?>
</body>
</html>
