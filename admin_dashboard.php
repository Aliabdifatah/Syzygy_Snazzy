<?php
session_start();
include 'header.php'; 

// Protect page: only allow admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!-- Main Container -->
<div class="container-fluid">
  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Admin Dashboard</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="adminNavbar">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <span class="nav-link">Welcome, <?= htmlspecialchars($_SESSION['first_name']) ?></span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 bg-light vh-100 p-4">
      <h5 class="mb-4">Menu</h5>
      <ul class="nav flex-column">
        <li class="nav-item mb-2">
          <a href="manage_products.php" class="nav-link text-dark">
            <i class="bi bi-box-seam me-2"></i>Manage Products
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="user_management.php" class="nav-link text-dark">
            <i class="bi bi-people me-2"></i>Manage Users
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="orders.php" class="nav-link text-dark">
            <i class="bi bi-receipt me-2"></i>View Orders
          </a>
        </li>
      </ul>
    </div>

    <!-- Main Content Area -->
    <div class="col-md-10 p-4">
      <div class="mb-4">
        <h1 class="mb-3">Admin Dashboard</h1>
        <p class="lead">Welcome, <?= htmlspecialchars($_SESSION['first_name']) ?>. You are logged in as an Admin.</p>
      </div>

      <!-- Dashboard Cards -->
      <div class="row">
        <!-- Card: Manage Products -->
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <i class="bi bi-box-seam display-4 text-primary me-3"></i>
                <div>
                  <h5 class="card-title">Manage Products</h5>
                  <p class="card-text">Add, update, or remove products.</p>
                </div>
              </div>
            </div>
            <div class="card-footer bg-transparent border-0">
              <a href="manage_products.php" class="btn btn-primary">Go</a>
            </div>
          </div>
        </div>

        <!-- Card: Manage Users -->
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <i class="bi bi-people display-4 text-success me-3"></i>
                <div>
                  <h5 class="card-title">Manage Users</h5>
                  <p class="card-text">View and update user accounts.</p>
                </div>
              </div>
            </div>
            <div class="card-footer bg-transparent border-0">
              <a href="user_management.php" class="btn btn-success">Go</a>
            </div>
          </div>
        </div>

        <!-- Card: View Orders -->
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <i class="bi bi-receipt display-4 text-warning me-3"></i>
                <div>
                  <h5 class="card-title">View Orders</h5>
                  <p class="card-text">Check orders and order details.</p>
                </div>
              </div>
            </div>
            <div class="card-footer bg-transparent border-0">
              <a href="orders.php" class="btn btn-warning">Go</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Additional Widgets (Optional) -->
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title">Recent Activity</h5>
              <p class="card-text">Overview of the latest system activity will be shown here.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title">System Statistics</h5>
              <p class="card-text">Metrics and charts coming soon.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Include Bootstrap Icons if not already in header -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<?php include 'footer.php'; ?>
