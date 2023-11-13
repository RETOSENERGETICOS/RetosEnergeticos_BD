<?php
session_start();

// Verificar si hay una sesión activa
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

// Botones disponibles predefinidos
$botones_disponibles = ['Documentación Técnica', 'Proveedores', 'Software'];

// Procesar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nuevo_email = $_POST["email"];
    $nueva_contrasena = $_POST["password"];
    $nuevos_permisos = $_POST["permisos"];
    $nuevos_botones = isset($_POST["botones"]) ? implode(',', $_POST["botones"]) : '';

    // Validar los datos (puedes agregar más validaciones según tus necesidades)

    // Insertar la nueva cuenta en la base de datos
    require_once '../includes/db_connection.php';
    $stmt = $conn->prepare("INSERT INTO usuarios (email, contrasena, permisos, botones) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nuevo_email, $nueva_contrasena, $nuevos_permisos, $nuevos_botones);
    $stmt->execute();
    $stmt->close();

    // Redirigir al panel de administrador después de agregar la cuenta
    header("Location: ver_cuentas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos/cuentas.css">

    <title>Agregar Cuenta</title>
</head>
<body>
    <form action="agregar_cuenta.php" method="post">
        <h1>Agregar usuarios</h1>

        <div class="llenado_user">
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="text" name="password" required>
        </div>
        <br>
        <div class="llenado_permiso">
        <label for="permisos">Permisos:</label>
        <select name="permisos" required>
            <option value="admin">Administrador</option>
            <option value="usuario">Usuario</option>
        </select>
        <br><br>
        <label for="botones">Bases de Datos que puede ver:</label>
        <?php
        foreach ($botones_disponibles as $boton) {
            echo "<input type='checkbox' name='botones[]' value='$boton'> $boton<br>";
        }
        ?>
        <br></div>
        <div class="boton_save" ><button type="submit">AGREGAR</button></div>
    </form>
    <div class="options_atras"><a href="panel_admin.php">Volver al panel</a></div>
</body>
</html>