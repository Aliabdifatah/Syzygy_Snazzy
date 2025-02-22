<?php
include 'db_connect.php';
include 'header.php'; 
$conn = getDatabaseConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare a statement to delete the product
    $stmt = $conn->prepare("DELETE FROM product WHERE ProductID = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Redirect to the products page after deletion
        header("Location: manage_products.php");
        exit;
    } else {
        echo "Error deleting product: " . $conn->error;
    }
    
    $stmt->close();
} else {
    echo "No product ID specified.";
}

$conn->close();
?>
<?php include 'footer.php'; ?>