<?php
session_start();

// Verificar si hay una sesión activa
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

// Obtener la información del usuario actual desde la base de datos
require_once '../includes/db_connection.php';

$email_usuario = $_SESSION['email'];

$stmt = $conn->prepare("SELECT botones FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email_usuario);
$stmt->execute();
$stmt->bind_result($botones_usuario);
$stmt->fetch();
$stmt->close();

// Convertir la cadena de botones en un array
$botones_usuario = explode(',', $botones_usuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos/panel_control.css">
    <title>Panel de Usuario</title>
</head>
<body>

    <h2>Bienvenido al panel de usuario</h2>

    <!-- Mostrar solo los botones seleccionados para el usuario -->
    <?php
    foreach ($botones_usuario as $boton) {
        echo "<a href='doc_tecnica_user.php'><button class='boton-azul'>$boton</button></a>";
    }
    ?>
    <br>
    <div class="options_logout">
    <a href="../includes/logout.php">Cerrar sesión</a>
    </div>
</body>
</html>