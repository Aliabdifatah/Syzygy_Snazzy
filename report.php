<?php
include "header.php";
include "db_connect.php";
$conn = getDatabaseConnection();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve filter values from GET parameters
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$monthFilter    = isset($_GET['month']) ? $_GET['month'] : '';

// Prepare an array of queries with dynamic filters applied
$queries = [];

// 1. Top Selling Products (filter by product category if set)
$sql = "SELECT p.ProductName, SUM(oi.Quantity) AS TotalUnitsSold, 
               SUM(oi.Quantity * oi.PricePerUnit) AS TotalRevenue 
        FROM OrderItem oi 
        JOIN Product p ON oi.ProductID = p.ProductID";
if ($categoryFilter != '') {
    $sql .= " WHERE p.CategoryID = " . intval($categoryFilter);
}
$sql .= " GROUP BY p.ProductName 
          ORDER BY TotalRevenue DESC";
$queries["Top Selling Products"] = $sql;

// 2. Revenue by Category (filter by category if set)
$sql = "SELECT c.CategoryName, SUM(oi.TotalPrice) AS TotalRevenue
        FROM OrderItem oi
        JOIN Product p ON oi.ProductID = p.ProductID
        JOIN Category c ON p.CategoryID = c.CategoryID";
if ($categoryFilter != '') {
    $sql .= " WHERE c.CategoryID = " . intval($categoryFilter);
}
$sql .= " GROUP BY c.CategoryName
          ORDER BY TotalRevenue DESC";
$queries["Revenue by Category"] = $sql;

// 3. Customer Purchase Summary (filter by month if set)
$sql = "SELECT cu.Name AS CustomerName, COUNT(o.OrderID) AS TotalOrders, SUM(i.TotalPrice) AS TotalSpent
        FROM Customer cu
        JOIN Orders o ON cu.CustomerID = o.CustomerID
        JOIN Invoice i ON o.OrderID = i.OrderID";
if ($monthFilter != '') {
    $sql .= " WHERE DATE_FORMAT(o.OrderDate, '%Y-%m') = '" . $conn->real_escape_string($monthFilter) . "'";
}
$sql .= " GROUP BY cu.CustomerID, cu.Name
          ORDER BY TotalSpent DESC";
$queries["Customer Purchase Summary"] = $sql;

// 4. Monthly Sales Performance (filter by month if set)
$sql = "SELECT DATE_FORMAT(o.OrderDate, '%Y-%m') AS Month, SUM(i.TotalPrice) AS MonthlyRevenue
        FROM Orders o
        JOIN Invoice i ON o.OrderID = i.OrderID";
if ($monthFilter != '') {
    $sql .= " WHERE DATE_FORMAT(o.OrderDate, '%Y-%m') = '" . $conn->real_escape_string($monthFilter) . "'";
}
$sql .= " GROUP BY Month
          ORDER BY Month ASC";
$queries["Monthly Sales Performance"] = $sql;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Analytics Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Sales Analytics Report</h2>
        
        <!-- Global Filter Form -->
        <form method="GET" class="mb-4">
            <div class="row g-3">
                <!-- Filter by Category -->
                <div class="col-md-6">
                    <label for="category" class="form-label">Filter by Category:</label>
                    <select class="form-select" name="category">
                        <option value="">All Categories</option>
                        <?php
                        // Get category list from the Category table
                        $catSql = "SELECT CategoryID, CategoryName FROM Category";
                        $catResult = $conn->query($catSql);
                        if ($catResult) {
                            while ($catRow = $catResult->fetch_assoc()) {
                                $selected = (isset($_GET['category']) && $_GET['category'] == $catRow['CategoryID']) ? "selected" : "";
                                echo "<option value='" . $catRow['CategoryID'] . "' $selected>" . htmlspecialchars($catRow['CategoryName']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <!-- Filter by Month -->
                <div class="col-md-6">
                    <label for="month" class="form-label">Filter by Month:</label>
                    <input type="month" class="form-control" name="month" value="<?php echo htmlspecialchars($monthFilter); ?>">
                </div>
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
        </form>
        
        <?php
        // Loop through each query and display its results in a separate table.
        foreach ($queries as $title => $sql) {
            echo "<h3 class='text-center mt-5'>$title</h3>";
            echo "<table class='table table-bordered table-striped'>";
            echo "<thead class='table-dark'><tr>";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                // Display column headers
                $fields = $result->fetch_fields();
                foreach ($fields as $field) {
                    echo "<th>" . htmlspecialchars($field->name) . "</th>";
                }
                echo "</tr></thead><tbody>";
                // Display data rows
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>No data available</td></tr>";
            }
            echo "</tbody></table>";
        }
        $conn->close();
        ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
