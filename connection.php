<?php
$servername = "127.0.0.1";
$username = "root";
$password = "calpoo@96"; 
$database = "placement_data";

$conn  = new mysqli($servername, $username, $password, database: $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo " ";
}

?>