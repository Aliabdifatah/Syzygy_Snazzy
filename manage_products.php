<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include 'header.php';
include 'db_connect.php';
$conn = getDatabaseConnection();

$sql = "SELECT * FROM Product";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>Manage Products</h2>
    <a href="add_product.php" class="btn btn-primary mb-3">Add New Product</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Cost Price</th>
                <th>Selling Price</th>
                <th>Stock Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['ProductID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ProductName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['CostPrice']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['SellingPrice']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['StockQty']) . "</td>";
                    echo "<td>
                            <a href='edit_product.php?id=" . $row['ProductID'] . "' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_product.php?id=" . $row['ProductID'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this product?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No products found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
$conn->close();
include 'footer.php';
?>

