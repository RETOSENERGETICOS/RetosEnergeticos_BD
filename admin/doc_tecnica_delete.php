<?php
session_start();

// Verificar si hay una sesión activa
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}
// Aquí va el contenido de tu panel de usuario
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ITEM"])) {
    $ITEM = $_POST["ITEM"];

    // Incluir el archivo de conexión
    require_once '../includes/db_connection.php';

    // Consulta SQL para eliminar el registro
    $query = "DELETE FROM documentacion_tecnica WHERE ITEM = $ITEM";

    if ($conn->query($query) === TRUE) {
        echo "<div align=center><br><img src=img/ok.png width=100px height=auto></div>";
        echo "<div align=center><br>Registro eliminado correctamente.</div>";
        echo "<div align=center><a href=doc_tecnica_admin.php><br>Volver al panel dashboard</a></div>";
    } else {
        echo "Error al eliminar el registro: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "No se proporcionó un ID válido para eliminar.";
}
?>