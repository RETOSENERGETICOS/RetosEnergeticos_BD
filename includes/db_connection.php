<!-- includes/db_connection.php -->

<?php
$servername = "localhost:8080";
$username = "root";
$password = "";
$database = "retosenergeticos_qas";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
