<?php
//define variables to access db
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'users');
// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// output error if connection is interrupted/not established
if($conn->connect_error) {
    die('Database error:' . $conn->connect_error);
}
?>