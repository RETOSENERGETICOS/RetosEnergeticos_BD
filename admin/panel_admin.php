<?php
session_start();

// Verificar si hay una sesión activa
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}
// Aquí va el contenido de tu panel de usuario
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
<form>
<h1>Bienvenido al panel de Administrador</h1>
<div class="options_user">
    <!-- Botón para agregar nuevas cuentas -->
    <a href="agregar_cuenta.php">Agregar usuarios</a>

    <!-- Botón para ver las cuentas existentes -->
    <a href="ver_cuentas.php">Editar usuarios</a>
</div>
    <br><br>
    <a href="doc_tecnica_admin.php">Documentación Técnica</a>
    <a href="">Proveedores</a>
    <a href="">Software</a>
</form>
    <br>
    <div class="options_logout">
    <a href="../includes/logout.php">Cerrar sesión</a>
    </div>
</body>
</html>