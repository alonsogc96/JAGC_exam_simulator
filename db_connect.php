<?php
//Change this according to your configuration
$servername = "localhost";
$username = "root";
$password = ""; // Cambia esta línea según tu configuración
$dbname = "exam_simulator";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

