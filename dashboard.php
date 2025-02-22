<?php
session_start();
include 'header.php'; 

// Check if the user is logged in; if not, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Syzygy Database Application</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file if needed -->
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h2>
    <p>You are logged in as: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
    <p>Your role: <?php echo htmlspecialchars($_SESSION['role']); ?></p>
    
    <!-- Add additional dashboard content as needed -->

    <p><a href="logout.php">Logout</a></p>
</body>
</html>
<?php include 'footer.php'; ?>
