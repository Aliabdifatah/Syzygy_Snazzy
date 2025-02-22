<?php
include 'db_connect.php'; 
include 'header.php'; 

$conn = getDatabaseConnection();

// Check if ID is set in URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convert ID to integer for safety
    
    // Prepare a statement to delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Redirect to the user management page after deletion
        header("Location: user_management.php?success=User deleted successfully");
        exit;
    } else {
        echo "Error deleting user: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    echo "No user ID specified.";
}

$conn->close();
include 'footer.php';
?>
