<?php
session_start();

// Verificar si hay una sesión activa
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

// Obtener las cuentas de la base de datos
require_once '../includes/db_connection.php';

// Obtener las cuentas de la base de datos
require_once '../includes/db_connection.php';

// Verificar si se ha enviado un formulario de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar"])) {
    $id_a_eliminar = $_POST["id_a_eliminar"];

    // Realizar la eliminación en la base de datos
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id_a_eliminar);
    $stmt->execute();
    $stmt->close();
}

$sql = "SELECT id, email, contrasena, permisos FROM usuarios";
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Output data of each row
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../estilos/tabla_cuentas.css">
        <title>Ver Cuentas</title>
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }

            th {
                background-color: #f2f2f2;
            }

            .acciones {
                display: flex;
                gap: 10px;
            }
        </style>
    </head>
    <body>
        <h1>Editar usuarios</h1>
        <table>
            <tr>
                <th>Correo Electrónico</th>
                <th>Contraseña</th>
                <th>Permiso / Privilegio</th>
                <th>Acciones</th>
            </tr>

            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["contrasena"] . "</td>";
                echo "<td>" . $row["permisos"] . "</td>";
                echo "<td class='acciones'>";


                echo "<form action='editar_cuenta.php' method='get'>";
                echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                echo "<button type='submit' class='editar_button'>";
                echo "<img src='../img/edit.svg' alt='Editar'>";
                echo "</button>";
                echo "</form>";

                echo "<form action='ver_cuentas.php' method='post'>";

                echo "<button type='submit' class='delete_button' name='eliminar' value='" . $row["id"] . "' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta cuenta?\")'>";
                echo "<img src='../img/delete.svg' alt='Eliminar'>";
                echo "</button>";
                echo "<input type='hidden' name='id_a_eliminar' value='" . $row["id"] . "'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <br>
        <div class="options_atras"><a href="panel_admin.php">Volver al panel</a></div>
    </body>
    </html>
    <?php
} else {
    echo "No hay cuentas para mostrar.";
}
$conn->close();
?>