<?php
session_start();
include 'header.php';

// Fetch user orders from the database (you need to replace this with your own logic)
include 'db_connect.php';
$conn = getDatabaseConnection();

$customer_id = $_SESSION['id']; // Assuming the customer ID is stored in session

// Fetch order history for the customer
$sql_orders = "SELECT * FROM Orders WHERE CustomerID = '$customer_id' ORDER BY OrderDate DESC";
$result_orders = mysqli_query($conn, $sql_orders);

?>

<div class="container mt-5">
    <h1 class="text-center">Your Order History</h1>

    <!-- If the customer has no orders -->
    <?php if (mysqli_num_rows($result_orders) == 0) { ?>
        <div class="alert alert-info">
            You haven't placed any orders yet.
        </div>
    <?php } else { ?>

        <!-- Orders Table -->
        <div class="table-responsive mt-4">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Order ID</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Total Amount</th>
                        <th scope="col">Payment Method</th>
                        <th scope="col">Shipping Method</th>
                        <th scope="col">Status</th>
                        <th scope="col">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($result_orders)) { ?>
                        <tr>
                            <td><?php echo $order['OrderID']; ?></td>
                            <td><?php echo $order['OrderDate']; ?></td>
                            <td>$<?php echo number_format($order['TotalAmount'], 2); ?></td>
                            <td><?php echo $order['PaymentMethod']; ?></td>
                            <td><?php echo $order['ShippingMethod']; ?></td>
                            <td><?php echo $order['Status']; ?></td>
                            <td><a href="order_details.php?order_id=<?php echo $order['OrderID']; ?>" class="btn btn-info btn-sm">View Details</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    <?php } ?>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

</div>

<?php
mysqli_close($conn);
include 'footer.php';
?>
