<?php
$conn = new mysqli("localhost", "root", "", "shopdb");

if ($conn->connect_error) {
    die("Database connection failed");
}
echo "connected!";
?>