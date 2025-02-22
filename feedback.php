<?php
session_start();
include 'db_connect.php';
include 'header.php';
$conn = getDatabaseConnection();

// Get order_id and customer_id from URL (or session)
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : null;

if (!$order_id || !$customer_id) {
    die("Order ID or Customer ID is missing.");
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission
    $rating = $_POST['rating'];
    $feedback_text = $_POST['feedback_text'];
    $feedback_date = date('Y-m-d');

    $sql = "INSERT INTO CustomerFeedback (CustomerID, OrderID, Rating, FeedbackText, FeedbackDate)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiss", $customer_id, $order_id, $rating, $feedback_text, $feedback_date);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success text-center'>Thank you for your feedback!</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>Failed to submit feedback. Please try again later.</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leave Feedback</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Minimal style to prevent horizontal scrolling */
    body {
      overflow-x: hidden;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow-lg w-100" style="max-width: 500px;">
      <div class="card-header bg-primary text-white text-center">
        <h2 class="mb-0">We Value Your Feedback</h2>
        <small>Tell us about your experience</small>
      </div>
      <div class="card-body">
        <?php if (!empty($message)) { echo $message; } ?>
        <form method="POST" action="">
          <div class="mb-3">
            <label for="rating" class="form-label">Rating (1 to 5)</label>
            <select name="rating" id="rating" class="form-select" required>
              <option value="" disabled selected hidden>Choose rating</option>
              <option value="1">1 - Very Poor</option>
              <option value="2">2 - Poor</option>
              <option value="3">3 - Average</option>
              <option value="4">4 - Good</option>
              <option value="5">5 - Excellent</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="feedback_text" class="form-label">Your Feedback</label>
            <textarea name="feedback_text" id="feedback_text" class="form-control" rows="4" placeholder="Share your thoughts here..." required></textarea>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-success btn-lg">Submit Feedback</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php
  include "footer.php"
  ?>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
