<?php 
function getDatabaseConnection(){
    $servername = "localhost";
    $username   = "root";
    $password   = "";  // Empty password
    $database   = "syzygysnazzydb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Error: Failed to connect to MySQL - " . $conn->connect_error);
    }

    return $conn;
}
?>

