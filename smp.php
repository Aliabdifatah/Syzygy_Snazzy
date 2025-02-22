<?php
// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format!";
    $error = true;
}

// Check if email exists
$statement = $dbconnection->prepare("SELECT id FROM users WHERE email = ?");
$statement->bind_param("s", $email);
$statement->execute();
$statement->store_result();
if ($statement->num_rows > 0) {
    $email_error = "Email is already registered!";
    $error = true;
}

// Insert user into the database users Table
$statement = $dbconnection->prepare(
    "INSERT INTO users (first_name, last_name, email, phone, address, password, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?)"
);
$statement->bind_param('sssssss', $first_name, $last_name, $email, $phone, $address, $password, $created_at);
$statement->execute();


// Prepare an SQL query to fetch user details based on email
$stmt = $conn->prepare("SELECT id, first_name, last_name, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $first_name, $last_name, $hashed_password, $role);

//Checks if the user exists and verifies the entered password against the hashed password stored in the database.
if ($stmt->fetch() && password_verify($password, $hashed_password)) {

//If the user is a customer, their CustomerID is retrieved from the Customer table and stored in a session variable.
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
//If the user is an admin, they are redirected to the admin dashboard.

    if (strtolower($role) === "admin") {
        header("Location: admin_dashboard.php");
        exit;
    }
//If they are a customer, they are redirected to the homepage (Index.php).

    header("Location: Index.php");
    exit;
    
}
?>