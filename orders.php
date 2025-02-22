<?php
session_start();
// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include 'header.php';
include 'db_connect.php';
$conn = getDatabaseConnection();

// Fetch all orders (you can join with Customer table for customer name if needed)
$sql = "SELECT OrderID, CustomerID, OrderDate FROM Orders ORDER BY OrderDate DESC";
$result = $conn->query($sql);
?>
<div class="container mt-5">
    <h2>All Orders</h2>
    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer ID</th>
                    <th>Order Date</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php while($order = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['OrderID']) ?></td>
                        <td><?= htmlspecialchars($order['CustomerID']) ?></td>
                        <td><?= htmlspecialchars($order['OrderDate']) ?></td>
                        
                            
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</div>
<?php
$conn->close();
include 'footer.php';
?>
