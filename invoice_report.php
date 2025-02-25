<?php
include "header.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice Report Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  
  
  <div class="container mt-4">
    <h2 class="text-center">Invoice Report</h2>
    
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
                  // Set the option as selected if it matches the GET parameter
                  $selected = (isset($_GET['customer']) && $_GET['customer'] == $custRow['CustomerID']) ? "selected" : "";
                  echo "<option value='" . $custRow['CustomerID'] . "' $selected>" . htmlspecialchars($custRow['Name']) . "</option>";
                }
              }
            ?>
          </select>
        </div>
        
        <!-- Filter by From Date -->
        <div class="col-md-4">
          <label for="from_date" class="form-label">From Date:</label>
          <input type="date" class="form-control" name="from_date" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ''; ?>">
        </div>
        
        <!-- Filter by To Date -->
        <div class="col-md-4">
          <label for="to_date" class="form-label">To Date:</label>
          <input type="date" class="form-control" name="to_date" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : ''; ?>">
        </div>
        
        <div class="col-md-12 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">Apply Filters</button>
        </div>
      </div>
    </form>
    
    <?php
      // Retrieve filter values from GET parameters
      $customerFilter = (isset($_GET['customer']) && $_GET['customer'] != "") ? $_GET['customer'] : "";
      $fromDate = (isset($_GET['from_date']) && $_GET['from_date'] != "") ? $_GET['from_date'] : "";
      $toDate = (isset($_GET['to_date']) && $_GET['to_date'] != "") ? $_GET['to_date'] : "";
      
      // Base query (as provided)
      $query = "SELECT i.InvoiceID, i.InvoiceDate, c.Name AS CustomerName, i.TotalPrice, i.TaxAmount
                FROM Invoice i
                JOIN Customer c ON i.CustomerID = c.CustomerID";
      
      // Build WHERE clause based on filter values
      $conditions = [];
      if ($customerFilter != "") {
        // Ensure an integer value for security
        $conditions[] = "c.CustomerID = " . intval($customerFilter);
      }
      if ($fromDate != "") {
        $conditions[] = "i.InvoiceDate >= '" . $conn->real_escape_string($fromDate) . "'";
      }
      if ($toDate != "") {
        $conditions[] = "i.InvoiceDate <= '" . $conn->real_escape_string($toDate) . "'";
      }
      
      // Append conditions if any filters are applied
      if (count($conditions) > 0) {
        $query .= " WHERE " . implode(" AND ", $conditions);
      }
      
      // Append ordering
      $query .= " ORDER BY i.InvoiceDate DESC";
      
      // Execute the query
      $result = $conn->query($query);
      
      if (!$result) {
          echo "<div class='alert alert-danger'>Error executing query: " . $conn->error . "</div>";
      }
    ?>
    
    <!-- Invoice Report Table -->
    <div class="table-responsive mt-4">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Invoice ID</th>
            <th>Invoice Date</th>
            <th>Customer Name</th>
            <th>Total Price</th>
            <th>Tax Amount</th>
          </tr>
        </thead>
        <tbody>
          <?php
            if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['InvoiceID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['InvoiceDate']) . "</td>";
                echo "<td>" . htmlspecialchars($row['CustomerName']) . "</td>";
                echo "<td>$" . htmlspecialchars($row['TotalPrice']) . "</td>";
                echo "<td>$" . htmlspecialchars($row['TaxAmount']) . "</td>";
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
