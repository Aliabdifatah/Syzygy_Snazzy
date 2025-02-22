<?php 
session_start();
include 'header.php'; 



if (isset($_SESSION["email"])){
    header("location: /Index.php");
    exit;
}



$first_name = "";
$last_name = "";
$email = "";
$phone = "";
$address = "";


$first_name_error = "";
$last_name_error = "";
$email_error = "";
$phone_error = "";
$address_error = "";
$password_error = "";
$confirm_password_error = "";


$error = false;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone =$_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate first name
    if (empty($first_name)) {
        $first_name_error = "First name is required";
        $error = true;
    }

    // Validate last name
    if (empty($last_name)) {
        $last_name_error = "Last name is required";
        $error = true;
    }


    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format!";
        $error = true;
    }

    // Check if email exists
    include "db_connect.php";
    $dbconnection = getDatabaseConnection();
    $statement = $dbconnection->prepare("SELECT id FROM users WHERE email = ?");
    $statement->bind_param("s", $email);
    $statement->execute();
    $statement->store_result();
    if ($statement->num_rows > 0) {
        $email_error = "Email is already registered!";
        $error = true;
    }
    $statement->close();




    // Validate phone
    if (!preg_match("/^(\+|00\d{1,3})?[- ]?\d{7,12}$/", $phone)) {
        $phone_error = "Invalid phone format!";
        $error = true;
    }

    // Validate password
    if (strlen($password) < 6) {
        $password_error = "Password must be at least 6 characters!";
        $error = true;
    }

    // Validate confirm password
    if ($confirm_password != $password) {
        $confirm_password_error = "Passwords do not match!";
        $error = true;
    }

// If no errors, insert into the database
if (!$error) {
    // Hash the password for storage
    $password = password_hash($password, PASSWORD_DEFAULT);
    $created_at = date('Y-m-d H:i:s');
    
    // Insert into users table
    $statement = $dbconnection->prepare(
        "INSERT INTO users (first_name, last_name, email, phone, address, password, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $statement->bind_param('sssssss', $first_name, $last_name, $email, $phone, $address, $password, $created_at);
    $statement->execute();
    $user_id = $statement->insert_id; // Get the inserted user ID
    $statement->close();

    // Generate CustomerID (auto-incremented or custom logic)
    $customer_id = uniqid("CUST"); // Example: "CUST63e18c9254d32"

    // Combine first and last name for the customer table
    $name = $first_name . " " . $last_name;

    // Insert data into the customer table
    $statement = $dbconnection->prepare(
        "INSERT INTO customer (CustomerID, Name, Email, Phone) 
        VALUES (?, ?, ?, ?)"
    );
    $statement->bind_param('ssss', $customer_id, $name, $email, $phone);
    $statement->execute();
    $statement->close();

    // Store session data
    $_SESSION["id"] = $user_id;
    $_SESSION["first_name"] = $first_name;
    $_SESSION["last_name"] = $last_name;
    $_SESSION["email"] = $email;
    $_SESSION["phone"] = $phone;
    $_SESSION["address"] = $address;
    $_SESSION["created_at"] = $created_at;

    // Redirect to homepage
    header("Location: Index.php");
    exit;
}




}

?>  <!-- Include Header -->

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="card shadow p-4">
                <h1 class="text-center mb-4">Register</h1>

                <form method="post">
                    
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">First Name*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="first_name" value="<?= $first_name ?>">
                            <span class="text-danger"><?= $first_name_error ?></span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Last Name*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="last_name" value="<?= $last_name ?>">
                            <span class="text-danger"><?= $last_name_error ?></span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Email*</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" name="email" value="<?= $email ?>">
                            <span class="text-danger"><?= $email_error ?></span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Phone*</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control" name="phone" value="<?= $phone ?>">
                            <span class="text-danger"><?= $phone_error ?></span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Address</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="address" value="<?= $address ?>">
                            <span class="text-danger"><?= $address_error ?></span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Password*</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="password" value="">
                            <span class="text-danger"><?= $password_error ?></span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Confirm Password*</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="confirm_password" value="">
                            <span class="text-danger"><?= $confirm_password_error ?></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                    <div class="offset-sm-4 col-sm-4 d-grid">
                        <button type="submit" class="btn btn-primary">Register</button>  
                    </div>

                    <div class="col-sm-4 d-grid">
                    <button type="submit" class="btn btn-outline-primary">Cancel</button>  
                    </div>

                     </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>  <!-- Include Footer --> 