<?php
session_start();

// Verificar si hay una sesión activa
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

// Obtener el ID de la cuenta a editar desde la URL
if (isset($_GET['id'])) {
    $id_a_editar = $_GET['id'];

    // Obtener la información de la cuenta desde la base de datos
    require_once '../includes/db_connection.php';

    $stmt = $conn->prepare("SELECT email, contrasena, permisos, botones FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id_a_editar);
    $stmt->execute();
    $stmt->bind_result($email, $contrasena, $permisos, $botones);
    $stmt->fetch();
    $stmt->close();

    // Convertir la cadena de botones en un array
    $botones_array = explode(',', $botones);

    // Procesar el formulario si se ha enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $nuevo_email = $_POST["email"];
        $nueva_contrasena = $_POST["password"];
        $nuevos_permisos = $_POST["permisos"];
        $nuevos_botones = isset($_POST["botones"]) ? implode(',', $_POST["botones"]) : '';

        // Validar los datos (puedes agregar más validaciones según tus necesidades)

        // Actualizar la cuenta en la base de datos
        $stmt = $conn->prepare("UPDATE usuarios SET email = ?, contrasena = ?, permisos = ?, botones = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nuevo_email, $nueva_contrasena, $nuevos_permisos, $nuevos_botones, $id_a_editar);
        $stmt->execute();
        $stmt->close();

        // Redirigir al panel de administrador después de editar la cuenta
        header("Location: ver_cuentas.php");
        exit();
    }
} else {
    // Si no se proporciona un ID válido en la URL, redirigir a ver_cuentas.php
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
    <title>Editar Cuenta</title>
</head>
<body>


    <form action="editar_cuenta.php?id=<?php echo $id_a_editar; ?>" method="post">
        
    <h1>Editar Cuenta</h1>
    <div class="llenado_user">

        <label for="email">Nuevo Correo Electrónico:</label>
        <input type="email" name="email" value="<?php echo $email; ?>" required>

        <label for="password">Nueva Contraseña:</label>
        <input type="text" name="password" value="<?php echo $contrasena; ?>" required>
        </div>

        <div class="llenado_permiso">
        <label for="permisos">Permisos Actual: <?php echo $permisos; ?></label>
        <select name="permisos" required>
            <option value="admin" <?php if ($permisos === 'admin') echo 'selected'; ?>>Administrador</option>
            <option value="usuario" <?php if ($permisos === 'usuario') echo 'selected'; ?>>Usuario</option>
        </select>

        <label for="botones[]">Botones Disponibles:</label>
        <?php
        // Mostrar casillas de verificación para los botones disponibles
        $botones_disponibles = ['Documentación Técnica', 'Proveedores', 'Software']; // Ajusta según tus necesidades
        foreach ($botones_disponibles as $boton) {
            $checked = in_array($boton, $botones_array) ? 'checked' : '';
            echo "<input type='checkbox' name='botones[]' value='$boton' $checked> $boton<br>";
        }
        ?>
            <br></div>
        <div class="boton_save" ><button type="submit">ACTUALIZAR</button></div>
    </form>
    <div class="options_atras"><a href="ver_cuentas.php">Volver atrás</a></div>
</body>
</html>