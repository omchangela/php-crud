<?php
include 'db.php';
include 'navbar.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM users WHERE id = $id";
    if ($conn->query($sql)) {
        header("Location: read.php");
        exit(); // Stop further script execution
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<a href="read.php">Go back to User List</a>
