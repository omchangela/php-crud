<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'crud_app';

// Create a connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
