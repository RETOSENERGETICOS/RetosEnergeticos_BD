<!-- includes/login_verification.php -->

<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Verificar las credenciales en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND contrasena = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Usuario autenticado correctamente
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $email;

        // Redirigir según los permisos
        if ($row['permisos'] == 'admin') {
            header("Location: ../admin/panel_admin.php");
        } elseif ($row['permisos'] == 'usuario') {
            header("Location: ../user/panel_usuario.php");
        } else {
            // Permiso desconocido, manejar según tus necesidades
            echo "Permiso desconocido";
        }

    } else {
        // Credenciales incorrectas, redirigir al formulario de inicio de sesión
        header("Location: ../index.php");
    }

    $conn->close();
}
?>