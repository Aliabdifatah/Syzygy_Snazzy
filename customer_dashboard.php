<?php
include "header.php";



?>
<?php
session_start();
include 'header.php'; 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header("Location: login.php");
    exit;
}
?>
<div class="container-fluid mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Customer Menu</h4>
                    <ul class="list-unstyled">
                        <li><a href="products.php" class="btn btn-outline-primary w-100 mb-2">View Products</a></li>
                        <li><a href="cart.php" class="btn btn-outline-primary w-100 mb-2">View Cart</a></li>
                        <li><a href="order_history.php" class="btn btn-outline-primary w-100 mb-2">Order History</a></li>
                        <li><a href="logout.php" class="btn btn-outline-danger w-100">Log Out</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h1 class="card-title">Customer Dashboard</h1>
                    <p class="lead">Welcome, <strong><?= htmlspecialchars($_SESSION['first_name']) ?>!</strong> You are logged in as a Customer.</p>
                    <div class="alert alert-info" role="alert">
                        <h4 class="alert-heading">Your Recent Activity</h4>
                        <p>You can view your orders, update your profile, or explore the products in our store.</p>
                    </div>
                    
                    <!-- Optional Add more sections here -->
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Latest Updates</h4>
                        <p>Check out the latest offers and updates available exclusively for you.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
