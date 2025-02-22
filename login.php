<?php
session_start();
include 'db_connect.php';
include 'header.php';
$conn = getDatabaseConnection();

$error = "";
$email = "";
$password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Normalize email and trim whitespace
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Email and Password are required!";
    } else {
        // Query the users table for credentials
        $stmt = $conn->prepare("SELECT id, first_name, last_name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $first_name, $last_name, $hashed_password, $role);

        if ($stmt->fetch() && password_verify($password, $hashed_password)) {
            // Free and close statement before starting new query
            $stmt->free_result();
            $stmt->close();
            
            // If the role is "customer" (case-insensitive), fetch the CustomerID from the Customer table
            if (strtolower($role) === "customer") {
                $stmt2 = $conn->prepare("SELECT CustomerID FROM Customer WHERE LOWER(Email) = ?");
                $stmt2->bind_param("s", $email);
                $stmt2->execute();
                $stmt2->store_result();
                $stmt2->bind_result($customer_id);
                if ($stmt2->fetch()) {
                    $_SESSION['customer_id'] = $customer_id;
                }
                $stmt2->free_result();
                $stmt2->close();
            }
            
            // Set common session variables
            $_SESSION['user_id']    = $id;
            $_SESSION['email']      = $email;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name']  = $last_name;
            $_SESSION['role']       = $role;

            // Redirect admin users to the admin dashboard
            if (strtolower($role) === "admin") {
                header("Location: admin_dashboard.php");
                exit;
            }

            // Redirect all other users to the index page
            header("Location: Index.php");
            exit;
        } else {
            $stmt->free_result();
            $stmt->close();
            $error = "Invalid email or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Syzygy Snazzy</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS if needed -->
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
       <div class="col-md-6">
           <div class="card shadow-sm">
               <div class="card-body">
                   <h2 class="card-title text-center mb-4">Login</h2>
                   <?php if (!empty($error)) { ?>
                       <div class="alert alert-danger"><?php echo $error; ?></div>
                   <?php } ?>
                   <form method="POST" action="login.php">
                       <div class="mb-3">
                           <label for="email" class="form-label">Email</label>
                           <input type="email" name="email" id="email" class="form-control" required value="<?php echo htmlspecialchars($email); ?>">
                       </div>
                       <div class="mb-3">
                           <label for="password" class="form-label">Password</label>
                           <input type="password" name="password" id="password" class="form-control" required>
                       </div>
                       <div class="d-grid">
                           <button type="submit" class="btn btn-primary">Login</button>
                       </div>
                   </form>
               </div>
           </div>
       </div>
    </div>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
