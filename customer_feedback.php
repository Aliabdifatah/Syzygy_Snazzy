<?php
include "header.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Customer Feedback Report Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  
  
  <div class="container mt-4">
    <h2 class="text-center">Customer Feedback Report</h2>
    
    <!-- Filter Form -->
    <form method="GET" class="mb-4">
      <div class="row g-3">
        <!-- Filter by Customer -->
        <div class="col-md-4">
          <label for="customer" class="form-label">Filter by Customer:</label>
          <select class="form-select" name="customer">
            <option value="">All Customers</option>
            <?php
              // Connect to the database
              
              include "db_connect.php";
              
              $conn = getDatabaseConnection();
              $custQuery = "SELECT CustomerID, Name FROM Customer";
              $custResult = $conn->query($custQuery);
              if ($custResult) {
                while ($custRow = $custResult->fetch_assoc()) {
                  $selected = (isset($_GET['customer']) && $_GET['customer'] == $custRow['CustomerID']) ? "selected" : "";
                  echo "<option value='" . $custRow['CustomerID'] . "' $selected>" . htmlspecialchars($custRow['Name']) . "</option>";
                }
              }
            ?>
          </select>
        </div>
        
        <!-- Filter by Order ID -->
        <div class="col-md-4">
          <label for="order" class="form-label">Filter by Order ID:</label>
          <input type="number" class="form-control" name="order" placeholder="Enter Order ID" value="<?php echo isset($_GET['order']) ? $_GET['order'] : ''; ?>">
        </div>
        
        <!-- Filter by Rating -->
        <div class="col-md-4">
          <label for="rating" class="form-label">Filter by Rating:</label>
          <select class="form-select" name="rating">
            <option value="">All Ratings</option>
            <?php
              for ($i = 1; $i <= 5; $i++) {
                $selected = (isset($_GET['rating']) && $_GET['rating'] == $i) ? "selected" : "";
                echo "<option value='$i' $selected>$i</option>";
              }
            ?>
          </select>
        </div>
        
        <div class="col-md-12 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">Apply Filters</button>
        </div>
      </div>
    </form>
    
    <?php
      // Retrieve filter values
      $customerFilter = (isset($_GET['customer']) && $_GET['customer'] != "") ? $_GET['customer'] : "";
      $orderFilter    = (isset($_GET['order']) && $_GET['order'] != "") ? $_GET['order'] : "";
      $ratingFilter   = (isset($_GET['rating']) && $_GET['rating'] != "") ? $_GET['rating'] : "";
      
      // Base query
      $query = "SELECT 
                  cf.feedbackID,
                  c.Name AS CustomerName,
                  cf.orderID,
                  cf.rating,
                  cf.FeedbackText
                FROM 
                  CustomerFeedback cf
                JOIN 
                  Customer c ON cf.customerID = c.CustomerID";
      
      // Build WHERE clause based on filters
      $conditions = [];
      if ($customerFilter != "") {
        $conditions[] = "c.CustomerID = " . intval($customerFilter);
      }
      if ($orderFilter != "") {
        $conditions[] = "cf.orderID = " . intval($orderFilter);
      }
      if ($ratingFilter != "") {
        $conditions[] = "cf.rating = " . intval($ratingFilter);
      }
      
      if (count($conditions) > 0) {
        $query .= " WHERE " . implode(" AND ", $conditions);
      }
      
      // Append ordering
      $query .= " ORDER BY cf.feedbackID DESC";
      
      // Execute the query
      $result = $conn->query($query);
      
      if (!$result) {
          echo "<div class='alert alert-danger'>Error executing query: " . $conn->error . "</div>";
      }
    ?>
    
    <!-- Customer Feedback Table -->
    <div class="table-responsive mt-4">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Feedback ID</th>
            <th>Customer Name</th>
            <th>Order ID</th>
            <th>Rating</th>
            <th>Feedback Text</th>
          </tr>
        </thead>
        <tbody>
          <?php
            if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['feedbackID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['CustomerName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['orderID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['rating']) . "</td>";
                echo "<td>" . htmlspecialchars($row['FeedbackText']) . "</td>";
                echo "</tr>";
              }
            } else {
              echo "<tr><td colspan='5' class='text-center'>No data available</td></tr>";
            }
            $conn->close();
          ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
