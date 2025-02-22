<?php
include 'db_connect.php';
include 'header.php'; 
$conn = getDatabaseConnection();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            text-decoration: none;
            color: blue;
        }
    </style>
</head>
<body>
    <h2>Product List</h2>
    <table>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Cost Price</th>
            <th>Selling Price</th>
            <th>Stock Quantity</th>
            <th>Actions</th>
        </tr>
        <?php
        // Adjust the table name if necessary.
        $sql = "SELECT * FROM product";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['ProductID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ProductName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['CostPrice']) . "</td>";
                echo "<td>" . htmlspecialchars($row['SellingPrice']) . "</td>";
                echo "<td>" . htmlspecialchars($row['StockQty']) . "</td>";
                echo "<td>
                        <a href='edit_product.php?id=" . $row['ProductID'] . "'>Edit</a> | 
                        <a href='delete_product.php?id=" . $row['ProductID'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a> | 
                        <a href='add_to_cart.php?id=" . $row['ProductID'] . "'>Add to Cart</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No products found.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
<?php
$conn->close();
?>
<?php include 'footer.php'; ?>

